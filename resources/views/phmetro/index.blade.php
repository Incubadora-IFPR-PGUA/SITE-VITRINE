@extends('home')

@section('conteudo')

<!-- Table -->
<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
      <table class="min-w-full bg-white table-auto w-full">
        <thead>
          <tr class="text-center">
            <th class="py-2 px-4 border-b relative" style="cursor: pointer">Data/Hora</th>
            <th class="py-2 px-4 border-b relative" style="cursor: pointer">PH</th>
            <th class="py-2 px-4 border-b relative" style="cursor: pointer">Escala</th>
          </tr>
        </thead>
        <tbody id="tableData"></tbody>
      </table>
    </div>
  </div>
</div>

<script src="{{ asset('js/reloadTable/phmetro.js') }}"></script>

@endsection