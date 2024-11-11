<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<style>
    .custom-color {
        color: #1E90FF;
    }

    .nav-links {
        display: flex;
        justify-content: space-around;
        padding: 10px;
        background-color: #f8f9fa;
    }

    .nav-link {
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        color: #007bff;
        transition: background-color 0.2s, color 0.2s;
    }

    .nav-link:hover {
        background-color: #e9ecef;
        color: #0056b3;
    }

    .nav-link.active {
        background-color: #007bff;
        color: #fff;
    }
</style>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-feather fa-2x custom-color"></i>
                    </a>
                </div>
                
                <div class="nav-links">
                @if(request()->routeIs('pendente.index') || request()->routeIs('cadastro.index') || request()->routeIs('registro.index'))
                    @can('hasFullPermission', App\Models\AnilhaCadastro::class)
                    <x-nav-link :href="route('cadastro.index')" :active="request()->routeIs('cadastro.index')" class="nav-link">
                        {{ __('Cadastro') }}
                    </x-nav-link>
                    @endcan
                    @can('hasFullPermission', App\Models\AnilhaPendente::class)
                    <x-nav-link :href="route('pendente.index')" :active="request()->routeIs('pendente.index')" class="nav-link">
                        {{ __('Pendente') }}
                    </x-nav-link>
                    @endcan
                    @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                    <x-nav-link :href="route('registro.index')" :active="request()->routeIs('registro.index')" class="nav-link">
                        {{ __('Registro') }}
                    </x-nav-link>
                    @endcan
                @else
                    @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                    <x-nav-link :href="route('registro.index')" class="nav-link">
                        {{ __('Smart-Anilhas') }}
                    </x-nav-link>
                    @endcan
                    @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                    <x-nav-link :href="route('smarthorta')" class="nav-link">
                        {{ __('Smart-Horta') }}
                    </x-nav-link>
                    @endcan
                    @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                    <x-nav-link :href="route('macaddress')" class="nav-link">
                        {{ __('MacAddress') }}
                    </x-nav-link>
                    @endcan
                @endif
                </div>

                <!-- Apagar depois isso -->
                <div class="nav-links">
                @if(request()->routeIs('pendente.index') || request()->routeIs('cadastro.index') || request()->routeIs('registro.index'))
                    <x-nav-link :href="route('cadastro.index')" :active="request()->routeIs('cadastro.index')" class="nav-link">
                        {{ __('Cadastro') }}
                    </x-nav-link>
                    <x-nav-link :href="route('pendente.index')" :active="request()->routeIs('pendente.index')" class="nav-link">
                        {{ __('Pendente') }}
                    </x-nav-link>
                    <x-nav-link :href="route('registro.index')" :active="request()->routeIs('registro.index')" class="nav-link">
                        {{ __('Registro') }}
                    </x-nav-link>
                @else
                    <x-nav-link :href="route('registro.index')" class="nav-link">
                        {{ __('ANILHAS') }}
                    </x-nav-link>
                    <x-nav-link :href="route('smarthorta')" class="nav-link">
                        {{ __('HORTA') }}
                    </x-nav-link>
                    <x-nav-link :href="route('macaddress')" class="nav-link">
                        {{ __('MACADDRESS') }}
                    </x-nav-link>
                    <x-nav-link :href="route('phmetro')" class="nav-link">
                        {{ __('PHMETRO') }}
                    </x-nav-link>
                @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="d-flex align-items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                            <div class="d-flex align-items-center me-2">
                                <img src="{{ asset('profile.jpeg') }}" alt="Avatar" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                            </div>
                            <div>Visitante</div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Perfil') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="d-flex align-items-center px-4">
                <img src="{{ asset('profile.jpeg') }}" alt="Avatar" class="img-fluid rounded-circle me-3" style="width: 40px; height: 40px;">
                <div>
                    <div class="font-medium text-base text-gray-800">Visitante</div>
                    <div class="font-medium text-sm text-gray-500">visitante@email.com</div>
                </div>
            </div>


            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>