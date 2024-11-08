<?php

namespace App\Http\Controllers;

use App\Models\AnilhaCadastro;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnilhaCadastroController extends Controller {
    protected $apiService;

    public function __construct(ApiService $apiService) {
        $this->apiService = $apiService;
    }

    public function index() {
        // $this->authorize('hasFullPermission', AnilhaCadastro::class);
        $data = $this->apiService->listarAnilhasCadastradas();
        return view('cadastro.index', compact('data'));
    }

    public function reload() {
        // $this->authorize('hasFullPermission', AnilhaCadastro::class);
        $data = $this->apiService->listarAnilhasCadastradas();
        return response()->json($data); 
    }

    public function update(Request $request, $id) {
        $this->authorize('hasFullPermission', AnilhaCadastro::class);
        $data = $this->apiService->obterAnilhaCadastradaPorId($id);
        if (isset($data)) {
            $dadosAtualizacao = [
                'name' => $request->input('name'),
                'updated_at' => now(),
            ];
            $this->apiService->atualizarAnilhaCadastrada($id, $dadosAtualizacao);
            return redirect()->route('cadastro.index')->with('success', 'Cadastro atualizado com sucesso!');
        }
        return redirect()->route('cadastro.index')->with('error', 'ERRO: CADASTRO NÃO ENCONTRADO!');
    }    
    
    public function destroy($id) {
        $this->authorize('hasFullPermission', AnilhaCadastro::class);
        $data = $this->apiService->obterAnilhaCadastradaPorId($id);
        if(isset($data)) {
            $data = $this->apiService->deletarAnilhaCadastrada($id);
            return redirect()->route('cadastro.index');
        }
        return "<h1>ERRO: ANILHA NÃO ENCONTRADA!</h1>";
    }
}