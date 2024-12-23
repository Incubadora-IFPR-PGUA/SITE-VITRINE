<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ApiService;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class MacAddressController extends Controller {
    protected $apiService;

    public function __construct(ApiService $apiService) {
        $this->apiService = $apiService;
    }

    public function index(Request $request) {
        $filters = $request->only(['data', 'permitido', 'localizacao']);
        $data = collect($this->apiService->listarMacAddressEmJson());

        $data = $data->sortByDesc(function ($macaddress) {
            return Carbon::parse($macaddress['data_hora_captura']);
        });

        $data = $data->map(function ($macaddress) {
            $horaCaptura = Carbon::parse($macaddress['data_hora_captura'])->setTimezone('America/Sao_Paulo')->format('H');
            $macaddress['permitido'] = ($horaCaptura >= 6 && $horaCaptura < 18) ? 'Sim' : 'Não';
            return $macaddress;
        });        
    
        // Extrair localizações 
        $localizacoes = $data->pluck('macAddress_esp.nome')->unique()->sort()->values();
    
        // Aplicar filtros
        if ($filters['data'] ?? null) {
            $data = $data->filter(function ($macaddress) use ($filters) {
                return Carbon::parse($macaddress['data_hora_captura'])->isSameDay(Carbon::parse($filters['data']));
            });
        }

        if ($filters['permitido'] ?? null) {
            $data = $data->filter(function ($macaddress) use ($filters) {
                return $macaddress['permitido'] === $filters['permitido'];
            });
        }        
    
        if ($filters['localizacao'] ?? null) {
            $data = $data->filter(function ($macaddress) use ($filters) {
                return strpos(strtolower($macaddress['macAddress_esp']['nome']), strtolower($filters['localizacao'])) !== false;
            });
        }

        $data = $data->map(function ($macaddress) {
            $latitude = $macaddress['macAddress_esp']['latitude'] ?? null;
            $longitude = $macaddress['macAddress_esp']['longitude'] ?? null;
    
            if (!is_numeric($latitude) || !is_numeric($longitude)) {
                $macaddress['macAddress_esp']['latitude'] = null;
                $macaddress['macAddress_esp']['longitude'] = null;
            }
            return $macaddress;
        });
    
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
    
        return view('macaddress.index', [
            'macaddress' => $paginatedData,
            'filters' => $filters,
            'localizacoes' => $localizacoes
        ]);
    }   
    
    public function recarregar() {
        $data = $this->apiService->listarMacAddressEmJson();
        return response()->json($data);
    }
}