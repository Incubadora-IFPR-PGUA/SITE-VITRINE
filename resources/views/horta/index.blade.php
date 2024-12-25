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
            <!-- Filtros -->
            <form method="GET" action="{{ route('smarthorta') }}">
                <div class="row mb-3">
                  <!-- Data -->
                  <div class="col">
                      <input type="date" name="data" class="form-control" value="{{ request()->get('data') }}" placeholder="Data">
                  </div>

                  <!-- Umidade solo -->
                  <div class="col">
                      <input type="text" name="umidade_solo" class="form-control" value="{{ request()->get('umidade_solo') }}" placeholder="Umidade do Solo">
                  </div>

                  <!-- Umidade ar -->
                  <div class="col">
                      <input type="text" name="umidade_ar" class="form-control" value="{{ request()->get('umidade_ar') }}" placeholder="Umidade do Ar">
                  </div>

                  <!-- Temperatura ar -->
                  <div class="col">
                      <input type="text" name="temperatura_ar" class="form-control" value="{{ request()->get('temperatura_ar') }}" placeholder="Temperatura do Ar">
                  </div>

                  <!-- Luz Ambiente -->
                  <div class="col">
                    <select name="luz_ambiente" class="form-control" placeholder="Luz Ambiente">
                        <option value="">Luz Ambiente</option>
                        @foreach ($luz as $luz_ambiente)
                            <option value="{{ $luz_ambiente }}" 
                                @if(request()->get('luz_ambiente') == $luz_ambiente) selected @endif>
                                {{ $luz_ambiente }}
                            </option>
                        @endforeach
                    </select>
                  </div>

                  <div class="col d-flex">
                      <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                      <!-- Botão Limpar Filtros -->
                      <a href="{{ route('smarthorta') }}" class="btn btn-secondary">Limpar Filtros</a>
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
                            Umidade do Solo
                            <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                                <i class="bi bi-filter"></i>
                            </a>
                        </th>
                        <th>
                            Umidade do Ar
                            <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                                <i class="bi bi-filter"></i>
                            </a>
                        </th>
                        <th>
                            Temperatura do Ar
                            <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                                <i class="bi bi-filter"></i>
                            </a>
                        </th>
                        <th>
                            Luz Ambiente
                            <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                                <i class="bi bi-filter"></i>
                            </a>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($smarthorta as $horta)
                        <tr 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalHorta{{ $loop->index }}" 
                            style="cursor: pointer;"
                            class="row-hover"
                        >
                          <td>{{ Carbon::parse($horta['hora_atualizacao'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                          <td>{{ number_format(round($horta['umidade_solo'], 1), 1) }}</td>
                          <td>{{ number_format(round($horta['umidade_ar'], 1), 1) }}</td>
                          <td>{{ number_format(round($horta['temperatura_ar'], 1), 1) }}</td>
                          <td>{{ $horta['luz_ambiente']}}</td>
                        </tr>

                        <!-- Modal para Exibir Detalhes -->
                        <div class="modal fade" id="modalHorta{{ $loop->index }}" tabindex="-1" aria-labelledby="modalLabel{{ $loop->index }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel{{ $loop->index }}">Detalhes da Horta</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Data:</strong> {{ Carbon::parse($horta['hora_atualizacao'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>
                                        <p><strong>Umidade do Solo:</strong> {{ number_format(round($horta['umidade_solo'], 1), 1) }}</p>
                                        <p><strong>Umidade do Ar:</strong> {{ number_format(round($horta['umidade_ar'], 1), 1) }}</p>
                                        <p><strong>Temperatura do Ar:</strong> {{ number_format(round($horta['temperatura_ar'], 1), 1) }}</p>
                                        <p><strong>Luz Ambiente:</strong> {{ $horta['luz_ambiente'] }}</p>
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
                {{ $smarthorta->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</body>

</html>

<script>
    const GOOGLE_MAPS_API_KEY = "{{ env('GOOGLE_MAPS_API_KEY') }}";
</script>

@endsection