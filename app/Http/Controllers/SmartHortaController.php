<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ApiService;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class SmartHortaController extends Controller {
    protected $apiService;

    public function __construct(ApiService $apiService) {
        $this->apiService = $apiService;
    }

    public function index(Request $request) {
        $filters = $request->only(['umidade_solo', 'umidade_ar', 'temperatura_ar', 'luz_ambiente', 'data']);
        $data = collect($this->apiService->listarHortaEmJson());

        $data = collect($data['data']);  // Transformar $data['data'] em uma coleção
        $luzAmbiente = $data->pluck('luz_ambiente')->unique()->sort()->values();

        $data = $data->sortByDesc(function ($smarthorta) {
            return Carbon::parse($smarthorta['hora_atualizacao']);
        });

        
        // Aplicar filtros
        if ($filters['umidade_solo'] ?? null) {
            $data = $data->filter(function ($smarthorta) use ($filters) {
                return strpos($smarthorta['umidade_solo'], $filters['umidade_solo']) !== false;
            });
        }
    
        if ($filters['umidade_ar'] ?? null) {
            $data = $data->filter(function ($smarthorta) use ($filters) {
                return strpos($smarthorta['umidade_ar'], $filters['umidade_ar']) !== false;
            });
        }

        if ($filters['temperatura_ar'] ?? null) {
            $data = $data->filter(function ($smarthorta) use ($filters) {
                return strpos($smarthorta['temperatura_ar'], $filters['temperatura_ar']) !== false;
            });
        }

        if ($filters['luz_ambiente'] ?? null) {
            $data = $data->filter(function ($smarthorta) use ($filters) {
                return strpos($smarthorta['luz_ambiente'], $filters['luz_ambiente']) !== false;
            });
        }
    
        if ($filters['data'] ?? null) {
            $data = $data->filter(function ($smarthorta) use ($filters) {
                return Carbon::parse($smarthorta['hora_atualizacao'])->isSameDay(Carbon::parse($filters['data']));
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
    
        return view('horta.index', [
            'smarthorta' => $paginatedData,
            'luz' => $luzAmbiente,
            'filters' => $filters
        ]);
    }   
    
    public function recarregar() {
        $data = $this->apiService->listarHortaEmJson();
        return response()->json($data);
    }
}