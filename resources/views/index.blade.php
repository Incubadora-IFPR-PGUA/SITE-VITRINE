<title>SMART HARPIA - Sistema Inteligente</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
    }

    .min-h-screen {
        min-height: 100vh;
        background: #f8f9fa;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <!-- @if(request()->routeIs('cadastro.index'))
            <div class="header-content">
                <i class="fas fa-list-check me-2"></i>
                Anilhas já cadastradas!
            </div>
        @endif
        @if(request()->routeIs('pendente.index'))
            <div class="header-content">
                <i class="fas fa-clock me-2"></i>
                Anilhas em espera para serem aceitas!
            </div>
        @endif
        @if(request()->routeIs('registro.index'))
            <div class="header-content">
                <i class="fas fa-database me-2"></i>
                Registros de anilhas já cadastradas!
            </div>
        @endif
        @if(request()->routeIs('phmetro.index'))
            <div class="header-content">
                <i class="fas fa-flask me-2"></i>
                Registros de anilhas já cadastradas!
            </div>
        @endif
        @if(request()->routeIs('home'))
            <div class="header-content">
                <i class="fas fa-home me-2"></i>
                Página Inicial
            </div>
        @endif -->
    </x-slot>
    @yield('conteudo')
</x-app-layout>

<style>
    .header-content {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: #2d3748;
        font-size: 1.25rem;
    }

    .header-content i {
        color: #667eea;
    }

    @media (max-width: 768px) {
        .header-content {
            font-size: 1.1rem;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>