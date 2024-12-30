<?php

namespace App\Http\Controllers;

use App\Models\AnilhaCadastro;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class AnilhaCadastroController extends Controller {
    protected $apiService;

    public function __construct(ApiService $apiService) {
        $this->apiService = $apiService;
    }

    public function index(Request $request) {
        $filters = $request->only(['data', 'nome', 'numero_anilha']);
        $data = collect($this->apiService->listarAnilhasCadastradasEmJson());

        $data = $data->sortByDesc(function ($cadastro) {
            return Carbon::parse($cadastro['created_at']);
        });

        $nome = $data->pluck('nome')->unique()->sort()->values();
        $anilha = $data->pluck('numero_anilha')->unique()->sort()->values();
        
        // Aplicar filtros
        if ($filters['data'] ?? null) {
            $data = $data->filter(function ($cadastro) use ($filters) {
                return Carbon::parse($cadastro['created_at'])->isSameDay(Carbon::parse($filters['data']));
            });
        }

        if ($filters['numero_anilha'] ?? null) {
            $data = $data->filter(function ($cadastro) use ($filters) {
                return strpos($cadastro['numero_anilha'], $filters['numero_anilha']) !== false;
            });
        }

        if ($filters['nome'] ?? null) {
            $data = $data->filter(function ($cadastro) use ($filters) {
                return strpos($cadastro['nome'], $filters['nome']) !== false;
            });
        }
    
        // Paginação
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $currentPageItems = $data->slice(($currentPage - 1) * $perPage, $perPage)->values();
    
        $paginatedData = new LengthAwarePaginator(
            $currentPageItems,
            $data->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        return view('cadastro.index', [
            'cadastros' => $paginatedData,
            'filters' => $filters,
            'nomes' => $nome,
            'anilhas' => $anilha,
        ]);
    }   
    
    public function recarregar() {
        $data = $this->apiService->listarAnilhasCadastradasEmJson();
        return response()->json($data);
    }

    public function update(Request $request, $id) {
        $data = $this->apiService->obterAnilhaCadastradaPorId($id);

        if (isset($data)) {
            $dadosAtualizacao = [
                'name' => $request->input('name'),
                'updated_at' => now(),
            ];
            $this->apiService->atualizarAnilhaCadastrada($id, $dadosAtualizacao);
            return redirect()->route('cadastro')->with('success', 'Cadastro atualizado com sucesso!');
        }
        return redirect()->route('cadastro')->with('error', 'ERRO: CADASTRO NÃO ENCONTRADO!');
    }    
    
    public function destroy($id) {
        // $this->authorize('hasFullPermission', AnilhaCadastro::class);
        $data = $this->apiService->obterAnilhaCadastradaPorId($id);
        if(isset($data)) {
            $data = $this->apiService->deletarAnilhaCadastrada($id);
            return redirect()->route('cadastro');
        }
        return "<h1>ERRO: ANILHA NÃO ENCONTRADA!</h1>";
    }
}