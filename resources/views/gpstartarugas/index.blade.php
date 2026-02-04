@extends('home')

@section('conteudo')

@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Tartarugas</title>
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
                        <form method="GET" action="{{ route('gpstartarugas') }}">
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="date" name="data" class="form-control" value="{{ request()->get('data') }}" placeholder="Data">
                                </div>
                                <div class="col">
                                    <input type="text" name="identificador" class="form-control" value="{{ request()->get('identificador') }}" placeholder="Identificador">
                                </div>
                                <div class="col">
                                    <select name="localizacao" class="form-control">
                                        <option value="">Localização</option>
                                        @foreach ($localizacoes as $loc)
                                            <option value="{{ $loc }}" @if(request()->get('localizacao') == $loc) selected @endif>{{ $loc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col d-flex">
                                    <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                                    <a href="{{ route('gpstartarugas') }}" class="btn btn-secondary">Limpar Filtros</a>
                                </div>
                            </div>
                        </form>

                        <table class="table table-bordered text-center table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Identificador</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Localização</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registros as $reg)
                                    <tr
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalGps{{ $loop->index }}"
                                        style="cursor: pointer;"
                                        class="row-hover"
                                    >
                                        <td>{{ Carbon::parse($reg['data_hora_atualizacao'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                                        <td>{{ $reg['identificador'] ?? '—' }}</td>
                                        <td>{{ number_format($reg['latitude'] ?? 0, 6) }}</td>
                                        <td>{{ number_format($reg['longitude'] ?? 0, 6) }}</td>
                                        <td>{{ $reg['localizacao'] ?? '—' }}</td>
                                    </tr>

                                    <div class="modal fade" id="modalGps{{ $loop->index }}" tabindex="-1" aria-labelledby="modalLabelGps{{ $loop->index }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabelGps{{ $loop->index }}">Detalhes - GPS Tartaruga</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Data:</strong> {{ Carbon::parse($reg['data_hora_atualizacao'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>
                                                    <p><strong>Identificador:</strong> {{ $reg['identificador'] ?? '—' }}</p>
                                                    <p><strong>Latitude:</strong> {{ number_format($reg['latitude'] ?? 0, 6) }}</p>
                                                    <p><strong>Longitude:</strong> {{ number_format($reg['longitude'] ?? 0, 6) }}</p>
                                                    <p><strong>Localização:</strong> {{ $reg['localizacao'] ?? '—' }}</p>
                                                    @if(isset($reg['latitude']) && isset($reg['longitude']) && is_numeric($reg['latitude']) && is_numeric($reg['longitude']))
                                                        <strong>Mapa:</strong>
                                                        <iframe
                                                            class="form-control mt-2"
                                                            width="100%"
                                                            height="200"
                                                            style="border: 0;"
                                                            src="https://www.google.com/maps/embed/v1/view?key={{ env('GOOGLE_MAPS_API_KEY') }}&center={{ $reg['latitude'] }},{{ $reg['longitude'] }}&zoom=15"
                                                            allowfullscreen
                                                            loading="lazy">
                                                        </iframe>
                                                    @else
                                                        <p class="mt-2">Localização não disponível para mapa.</p>
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

                        <div class="d-flex justify-content-center">
                            {{ $registros->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico -->
            <div id="graficoContainer" style="display:none;">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="border shadow rounded p-3" style="width: 100%; max-width: 100%; height: 500px; margin-bottom: 50px;">
                        <canvas id="gpsTartarugasGrafico" style="width: 100%; height: 100%;"></canvas>
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
        const ctx = document.getElementById('gpsTartarugasGrafico').getContext('2d');
        const responseData = {!! json_encode($registros) !!};

        const data = responseData.data || [];
        if (!Array.isArray(data) || data.length === 0) {
            return;
        }

        const labels = data.map(item => formatDate(item.data_hora_atualizacao));
        const latValues = data.map(item => item.latitude || 0);
        const lngValues = data.map(item => item.longitude || 0);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Latitude',
                        data: latValues,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: 'Longitude',
                        data: lngValues,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: false
                    }
                ]
            },
            options: {
                scales: {
                    x: { beginAtZero: true },
                    y: { beginAtZero: true }
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection
