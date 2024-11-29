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
                            <td>{{ $phmetro['escala']}}</td>
                            <td>{{ $phmetro['macAddress']['nome'] }}</td>
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
                                        <p><strong>ESP32:</strong> {{ $phmetro['macAddress']['nome'] }}</p>
                                        <p><strong>Descrição:</strong> {{ $phmetro['macAddress']['descricao'] }}</p>
                                        <strong>Localização:</strong>
                                        @if($phmetro['macAddress']['latitude'] && $phmetro['macAddress']['longitude'])
                                            <iframe 
                                                class="form-control" 
                                                id="mapFrame" 
                                                width="100%" 
                                                height="100%" 
                                                style="border: 0;" 
                                                src="https://www.google.com/maps/embed/v1/view?key={{ env('GOOGLE_MAPS_API_KEY') }}&center={{ $phmetro['macAddress']['latitude'] }},{{ $phmetro['macAddress']['longitude'] }}&zoom=15" 
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
</body>
</html>

<script>
    const GOOGLE_MAPS_API_KEY = "{{ env('GOOGLE_MAPS_API_KEY') }}";
</script>

@endsection