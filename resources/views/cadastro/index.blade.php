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
    <title>Lista de Anilhas Cadastradas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<!-- Tela de Carregamento -->
<!-- <div id="loadingScreen" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(255, 255, 255, 0.8); z-index:10000; display:flex; align-items:center; justify-content:center;">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Carregando...</span>
    </div>
</div> -->

<body>
    <div class="container mt-5 d-flex justify-content-center align-items-center">
        <div class="w-100">
          <!-- Filtros -->
          <form method="GET" action="{{ route('cadastro') }}">
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
                  <a href="{{ route('cadastro') }}" class="btn btn-secondary">Limpar Filtros</a>
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
                @foreach ($cadastros as $anilha)
                    <tr 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalCadastro{{ $loop->index }}" 
                        style="cursor: pointer;"
                        class="row-hover"
                    >
                      <td>{{ Carbon::parse($anilha['created_at'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                      <td>{{ $anilha['nome']}}</td>
                      <td>{{ $anilha['numero_anilha']}}</td>
                    </tr>

                    <!-- Modal para Exibir Detalhes -->
                    <div class="modal fade" id="modalCadastro{{ $loop->index }}" tabindex="-1" aria-labelledby="modalLabel{{ $loop->index }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $loop->index }}">Detalhes da Anilha</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ url('/cadastroUpdate/'.$anilha['id']) }}">
                                        @csrf
                                        @method('PUT')
                                        <p><strong>Data:</strong> {{ Carbon::parse($anilha['created_at'])->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>
                                        <div class="mb-3">
                                            <label for="nome{{ $loop->index }}" class="form-label"><strong>Nome:</strong></label>
                                            <input type="text" name="nome" id="nome{{ $loop->index }}" class="form-control" value="{{ $anilha['nome'] }}" required>
                                        </div>
                                        <p><strong>Número da Anilha:</strong> {{ $anilha['numero_anilha'] }}</p>
                                        <div class="modal-footer">
                                            <!-- Formulário de Exclusão -->
                                            <form method="POST" action="{{ url('/cadastroDelete/'.$anilha['id']) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger mt-2">Deletar</button>
                                            </form>
                                            <button type="submit" class="btn btn-primary">Salvar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            </tbody>
          </table>

          <!-- Paginação -->
          <div class="d-flex justify-content-center">
              {{ $cadastros->appends(request()->query())->links() }}
          </div>
        </div>
    </div>
</body>

</html>

@endsection