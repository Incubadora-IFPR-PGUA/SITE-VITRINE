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
            <table class="table table-bordered text-center">
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
                        <tr>
                            <td>{{ Carbon::parse($phmetro['data_hora_atualizacao'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format(round($phmetro['ph'], 1), 1) }}</td>
                            <td>{{ $phmetro['escala']}}</td>
                            <td>{{ $phmetro['macAddress']['nome'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginação -->
            <div class="d-flex justify-content-center">
                {{ $phmetros->links() }}
            </div>
        </div>
    </div>
</body>
</html>

@endsection