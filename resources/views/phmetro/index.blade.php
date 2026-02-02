@extends('home')

@section('conteudo')

@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Phmetros</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5 d-flex justify-content-center align-items-center">
        <div class="w-100">
            <!-- Botões para alternar entre Tabela e Gráfico -->
            <div class="mb-3 d-flex justify-content-start">
                <button class="btn btn-outline-primary me-2 active" id="btnTabela" onclick="showTabela()">Tabela</button>
                <button class="btn btn-outline-primary" id="btnGrafico" onclick="showGrafico()">Gráfico</button>
            </div>

            <!-- Tabela -->
            <div id="tabelaContainer">
                <div class="container mt-5 d-flex justify-content-center align-items-center">
                    <div class="w-100">
                        <form method="GET" action="{{ route('phmetro') }}">
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="date" name="data" class="form-control" value="{{ request()->get('data') }}" placeholder="Data">
                                </div>
                                <div class="col">
                                    <input type="text" name="ph" class="form-control" value="{{ request()->get('ph') }}" placeholder="Filtrar por Ph">
                                </div>
                                <div class="col">
                                    <select name="escala" class="form-control">
                                        <option value="">Escala</option>
                                        @foreach ($escalas as $escala)
                                            <option value="{{ $escala }}" 
                                                @if(request()->get('escala') == $escala) selected @endif>
                                                {{ $escala }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <select name="localizacao" class="form-control">
                                        <option value="">Localização</option>
                                        @foreach ($localizacoes as $localizacao)
                                            <option value="{{ $localizacao }}" 
                                                @if(request()->get('localizacao') == $localizacao) selected @endif>
                                                {{ $localizacao }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col d-flex">
                                    <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                                    <!-- Botão Limpar Filtros -->
                                    <a href="{{ route('phmetro') }}" class="btn btn-secondary">Limpar Filtros</a>
                                </div>
                            </div>
                        </form>

                        <!-- Tabela -->
                        <table class="table table-bordered text-center table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        Data
                                        <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                                            <i class="bi bi-filter"></i>
                                        </a>
                                    </th>
                                    <th>
                                        Ph
                                        <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                                            <i class="bi bi-filter"></i>
                                        </a>
                                    </th>
                                    <th>
                                        Escala
                                        <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                                            <i class="bi bi-filter"></i>
                                        </a>
                                    </th>
                                    <th>
                                        Localização
                                        <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                                            <i class="bi bi-filter"></i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($phmetros as $phmetro)
                                    <tr 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalPhmetro{{ $loop->index }}" 
                                        style="cursor: pointer;"
                                        class="row-hover"
                                    >
                                        <td>{{ Carbon::parse($phmetro['data_hora_atualizacao'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                                        <td>{{ number_format(round($phmetro['ph'], 1), 1) }}</td>
                                        <td>{{ $phmetro['escala'] }}</td>
                                        <td>{{ $phmetro['macAddress']['nome'] ?? '—' }}</td>
                                    </tr>

                                    <!-- Modal para Exibir Detalhes -->
                                    <div class="modal fade" id="modalPhmetro{{ $loop->index }}" tabindex="-1" aria-labelledby="modalLabel{{ $loop->index }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel{{ $loop->index }}">Detalhes do Phmetro</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Data:</strong> {{ Carbon::parse($phmetro['data_hora_atualizacao'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>
                                                    <p><strong>Ph:</strong> {{ number_format(round($phmetro['ph'], 1), 1) }}</p>
                                                    <p><strong>Escala:</strong> {{ $phmetro['escala'] }}</p>
                                                    <p><strong>ESP32:</strong> {{ $phmetro['macAddress']['nome'] ?? '—' }}</p>
                                                    <p><strong>Descrição:</strong> {{ $phmetro['macAddress']['descricao'] ?? '—' }}</p>
                                                    <strong>Localização:</strong>
                                                    @if(($phmetro['macAddress']['latitude'] ?? null) && ($phmetro['macAddress']['longitude'] ?? null))
                                                        <iframe 
                                                            class="form-control" 
                                                            id="mapFrame" 
                                                            width="100%" 
                                                            height="100%" 
                                                            style="border: 0;" 
                                                            src="https://www.google.com/maps/embed/v1/view?key={{ env('GOOGLE_MAPS_API_KEY') }}&center={{ $phmetro['macAddress']['latitude'] ?? 0 }},{{ $phmetro['macAddress']['longitude'] ?? 0 }}&zoom=15" 
                                                            allowfullscreen 
                                                            loading="lazy">
                                                        </iframe>
                                                    @else
                                                        <p>Localização inválida ou não disponível.</p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Paginação -->
                        <div class="d-flex justify-content-center">
                            {{ $phmetros->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico -->
            <div id="graficoContainer" style="display:none;">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="border shadow rounded p-3" style="width: 100%; max-width: 100%; height: 500px; margin-bottom: 50px;">
                        <canvas id="phmetroGrafico" style="width: 100%; height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    function showTabela() {
        document.getElementById('tabelaContainer').style.display = 'block';
        document.getElementById('graficoContainer').style.display = 'none';
        document.getElementById('btnTabela').classList.add('active');
        document.getElementById('btnGrafico').classList.remove('active');
    }

    function showGrafico() {
        document.getElementById('tabelaContainer').style.display = 'none'; 
        document.getElementById('graficoContainer').style.display = 'block';
        document.getElementById('btnGrafico').classList.add('active');
        document.getElementById('btnTabela').classList.remove('active');
        loadGrafico();
    }

    function loadGrafico() {
        const ctx = document.getElementById('phmetroGrafico').getContext('2d');
        const responseData = {!! json_encode($phmetros) !!};

        const data = responseData.data;
        if (!Array.isArray(data)) {
            console.error('Os dados não são um array');
            return;
        }

        const labels = data.map(item => formatDate(item.data_hora_atualizacao));
        const phValues = data.map(item => item.ph);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Valores de pH',
                    data: phValues,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: { 
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${day}/${month}/${year} ${hours}:${minutes}`;
    }
</script>

<script>const GOOGLE_MAPS_API_KEY = "{{ env('GOOGLE_MAPS_API_KEY') }}";</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection