<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ApiService;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class PhmetroController extends Controller {
    protected $apiService;

    public function __construct(ApiService $apiService) {
        $this->apiService = $apiService;
    }


    public function index(Request $request) {
        $filters = $request->only(['ph', 'escala', 'data', 'localizacao']);
        $data = collect($this->apiService->listarPhmetroEmJson());
        $localizacoes = $data->pluck('macAddress.nome')->unique()->sort()->values();
        $escalas = $data->pluck('escala')->unique()->sort()->values();

        //dd($filters['data']);

        if ($filters['ph'] ?? null) {
            $data = $data->filter(function ($phmetro) use ($filters) {
                return strpos($phmetro['ph'], $filters['ph']) !== false;
            });
        }
    
        if ($filters['escala'] ?? null) {
            $data = $data->filter(function ($phmetro) use ($filters) {
                return strpos($phmetro['escala'], $filters['escala']) !== false;
            });
        }
    
        if ($filters['data'] ?? null) {
            $data = $data->filter(function ($phmetro) use ($filters) {
                return Carbon::parse($phmetro['data_hora_atualizacao'])->isSameDay(Carbon::parse($filters['data']));
            });
        }

        if ($filters['localizacao'] ?? null) {
            $data = $data->filter(function ($phmetro) use ($filters) {
                return strpos(strtolower($phmetro['macAddress']['nome']), strtolower($filters['localizacao'])) !== false;
            });
        }
    
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

        return view('phmetro.index', ['phmetros' => $paginatedData, 'filters' => $filters, 'localizacoes' => $localizacoes, 'escalas' => $escalas]);
    }
    

    public function recarregar() {
        $data = $this->apiService->listarPhmetroEmJson();
        return response()->json($data);
    }
}