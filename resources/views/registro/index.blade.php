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
    <title>Lista de Registros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="w-100 d-flex justify-content-center mb-3">
            <h2>
                <span class="text-primary">
                    {{ $registros->filter(fn($item) => $item['status'] === true)->count() }}
                </span>
                <span> AVES NO NINHO</span>
            </h2>
        </div>
        <!-- Filtros -->
        <form method="GET" action="{{ route('registro') }}">
        <div class="row mb-3">
            <!-- Data -->
            <div class="col">
                <input type="date" name="data" class="form-control" value="{{ request()->get('data') }}" placeholder="Data">
            </div>

            <!-- Nome -->
            <div class="col">
                <select name="nome" class="form-control">
                    <option value="">Nome</option>
                    @foreach ($nomes as $nome)
                        <option value="{{ $nome }}" 
                            @if(request()->get('nome') == $nome) selected @endif>
                            {{ $nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Anilha -->
            <div class="col">
                <select name="numero_anilha" class="form-control">
                    <option value="">Anilha</option>
                    @foreach ($anilhas as $anilha)
                        <option value="{{ $anilha }}" 
                            @if(request()->get('anilha') == $anilha) selected @endif>
                            {{ $anilha }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col d-flex">
                <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                <!-- Botão Limpar Filtros -->
                <a href="{{ route('registro') }}" class="btn btn-secondary">Limpar Filtros</a>
            </div>
        </div>
        </form>

        <!-- Tabela -->
        <table class="table table-bordered text-center table-hover">
            <thead>
                <tr>
                    <th>
                        Data/Hora
                        <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                            <i class="bi bi-filter"></i>
                        </a>
                    </th>
                    <th>
                        Nome
                        <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                            <i class="bi bi-filter"></i>
                        </a>
                    </th>
                    <th>
                        Número Anilha
                        <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                            <i class="bi bi-filter"></i>
                        </a>
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($registros as $anilha)
                    <tr 
                        style="cursor: pointer;"
                        class="row-hover"
                    >
                        <td>{{ Carbon::parse($anilha['updated_at'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                        <td>{{ $anilha['nome']}}</td>
                        <td>{{ $anilha['numero_anilha']}}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <!-- Paginação -->
        <div class="d-flex justify-content-center">
            {{ $registros->appends(request()->query())->links() }}
        </div>
        </div>
    </div>
</body>

</html>

@endsection