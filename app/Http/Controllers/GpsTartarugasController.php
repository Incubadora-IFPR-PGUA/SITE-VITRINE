<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class GpsTartarugasController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['data', 'identificador', 'localizacao']);

        // Dados de exemplo (estrutura igual ao phmetro - pode ser trocada por API depois)
        $data = collect([
            [
                'id' => 1,
                'data_hora_atualizacao' => now()->subHours(2)->toIso8601String(),
                'identificador' => 'TART-001',
                'latitude' => -25.4284,
                'longitude' => -49.2733,
                'localizacao' => 'Curitiba - PR',
            ],
            [
                'id' => 2,
                'data_hora_atualizacao' => now()->subHours(5)->toIso8601String(),
                'identificador' => 'TART-002',
                'latitude' => -25.4402,
                'longitude' => -49.2690,
                'localizacao' => 'Ponta Grossa - PR',
            ],
            [
                'id' => 3,
                'data_hora_atualizacao' => now()->subDay()->toIso8601String(),
                'identificador' => 'TART-003',
                'latitude' => -25.5163,
                'longitude' => -49.2504,
                'localizacao' => 'São José dos Pinhais - PR',
            ],
        ]);

        $data = $data->sortByDesc(function ($item) {
            return Carbon::parse($item['data_hora_atualizacao']);
        });

        $identificadores = $data->pluck('identificador')->unique()->sort()->values();
        $localizacoes = $data->pluck('localizacao')->unique()->sort()->values();

        if ($filters['data'] ?? null) {
            $data = $data->filter(function ($item) use ($filters) {
                return Carbon::parse($item['data_hora_atualizacao'])->isSameDay(Carbon::parse($filters['data']));
            });
        }

        if ($filters['identificador'] ?? null) {
            $data = $data->filter(function ($item) use ($filters) {
                return strpos($item['identificador'], $filters['identificador']) !== false;
            });
        }

        if ($filters['localizacao'] ?? null) {
            $data = $data->filter(function ($item) use ($filters) {
                return strpos(strtolower($item['localizacao']), strtolower($filters['localizacao'])) !== false;
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

        return view('gpstartarugas.index', [
            'registros' => $paginatedData,
            'filters' => $filters,
            'identificadores' => $identificadores,
            'localizacoes' => $localizacoes,
        ]);
    }

    public function recarregar()
    {
        return response()->json([]);
    }
}
