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
            <form method="GET" action="{{ route('macaddress') }}">
                <div class="row mb-3">
                  <!-- Filtro de data -->
                  <div class="col">
                      <input type="date" name="data" class="form-control" value="{{ request()->get('data') }}" placeholder="Data">
                  </div>
                  <!-- Filtro de permissões -->
                  <div class="col">
                      <select name="permitido" class="form-control">
                        <option value="">Permitido</option>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                      </select>
                  </div>
                  <!-- Filtro de localizacao (esp) -->
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
                      <a href="{{ route('macaddress') }}" class="btn btn-secondary">Limpar Filtros</a>
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
                            Permitido
                            <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                                <i class="bi bi-filter"></i>
                            </a>
                        </th>
                        <th>
                            Qual ESP pegou
                            <a href="#" class="ms-2" title="Filtrar" data-bs-toggle="tooltip">
                                <i class="bi bi-filter"></i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($macaddress as $mac)
                        <tr 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalMacAddress{{ $loop->index }}" 
                            style="cursor: pointer;"
                            class="row-hover"
                        >                        
                          <td>{{ Carbon::parse($mac['data_hora_captura'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                          @php
                              $horaCaptura = Carbon::parse($mac['data_hora_captura'])->setTimezone('America/Sao_Paulo')->format('H');
                          @endphp
                          <td class="@if($horaCaptura >= 6 && $horaCaptura < 18) table-success @else table-danger @endif">
                              @if($horaCaptura >= 6 && $horaCaptura < 18)
                                  Sim
                              @else
                                  Não
                              @endif
                          </td>
                          <td>{{ $mac['macAddress_esp']['nome'] }}</td>
                        </tr>

                        <!-- Modal para Exibir Detalhes -->
                        <div class="modal fade" id="modalMacAddress{{ $loop->index }}" tabindex="-1" aria-labelledby="modalLabel{{ $loop->index }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel{{ $loop->index }}">Detalhes do MacAddress</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <p><strong>Data:</strong> {{ Carbon::parse($mac['data_hora_captura'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>
                                      <p><strong>Endereço MAC:</strong> {{ $mac['MAC'] }} </p>
                                      <p><strong>Fabricante:</strong> {{ $mac['fabricante'] }} </p>
                                      <strong>Localização:</strong>
                                      @if($mac['macAddress_esp']['latitude'] && $mac['macAddress_esp']['longitude'])
                                          <iframe 
                                              class="form-control" 
                                              id="mapFrame" 
                                              width="100%" 
                                              height="100%" 
                                              style="border: 0;" 
                                              src="https://www.google.com/maps/embed/v1/view?key={{ env('GOOGLE_MAPS_API_KEY') }}&center={{ $mac['macAddress_esp']['latitude'] }},{{ $mac['macAddress_esp']['longitude'] }}&zoom=15" 
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
                {{ $macaddress->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</body>

<script>
    const GOOGLE_MAPS_API_KEY = "{{ env('GOOGLE_MAPS_API_KEY') }}";
</script>

@endsection