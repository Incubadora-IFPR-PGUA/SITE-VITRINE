<?php

namespace App\Http\Controllers;

use App\Models\AnilhaRegistro;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class AnilhaRegistroController extends Controller {
    protected $apiService;

    public function __construct(ApiService $apiService) {
        $this->apiService = $apiService;
    }

    public function index(Request $request) {
        $filters = $request->only(['data', 'nome', 'numero_anilha']);
        $data = collect($this->apiService->listarAnilhasCadastradasEmJson());

        $data = $data->filter(function ($registro) {
            return isset($registro['status']) && $registro['status'] === true;
        });
        
        $data = $data->sortByDesc(function ($registro) {
            return Carbon::parse($registro['updated_at']);
        });

        $nome = $data->pluck('nome')->unique()->sort()->values();
        $anilha = $data->pluck('numero_anilha')->unique()->sort()->values();
        
        // Aplicar filtros
        if ($filters['data'] ?? null) {
            $data = $data->filter(function ($registro) use ($filters) {
                return Carbon::parse($registro['created_at'])->isSameDay(Carbon::parse($filters['data']));
            });
        }

        if ($filters['numero_anilha'] ?? null) {
            $data = $data->filter(function ($registro) use ($filters) {
                return strpos($registro['numero_anilha'], $filters['numero_anilha']) !== false;
            });
        }

        if ($filters['nome'] ?? null) {
            $data = $data->filter(function ($registro) use ($filters) {
                return strpos($registro['nome'], $filters['nome']) !== false;
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
    
        return view('registro.index', [
            'registros' => $paginatedData,
            'filters' => $filters,
            'nomes' => $nome,
            'anilhas' => $anilha,
        ]);
    }   
    
    public function recarregar() {
        $data = $this->apiService->listarAnilhasRegistradasEmJson();
        return response()->json($data);
    }
}