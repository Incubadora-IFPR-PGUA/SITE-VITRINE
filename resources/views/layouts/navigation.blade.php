<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    .navbar-professional {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 1000;
    }

    .navbar-professional .logo-container {
        transition: transform 0.3s ease;
    }

    .navbar-professional .logo-container:hover {
        transform: scale(1.1);
    }

    .navbar-professional .logo-icon {
        color: #ffffff;
        font-size: 1.75rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }

    .nav-menu-links {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: 2rem;
    }

    .nav-menu-item {
        position: relative;
    }

    .nav-menu-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        text-decoration: none;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .nav-menu-link:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .nav-menu-link.active {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.4);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .nav-menu-link i {
        font-size: 1rem;
    }

    .user-dropdown-container {
        position: relative;
        z-index: 1001;
    }

    .user-dropdown-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .user-dropdown-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .user-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.3);
        object-fit: cover;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .dropdown-content {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-top: 0.5rem;
        position: relative;
        z-index: 9999;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.25rem;
        color: #374151;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f3f4f6;
        color: #111827;
    }

    .dropdown-item i {
        width: 1.25rem;
        color: #6b7280;
    }

    .dropdown-item.logout {
        color: #dc2626;
        border-top: 1px solid #e5e7eb;
    }

    .dropdown-item.logout:hover {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .dropdown-item.logout i {
        color: #dc2626;
    }

    .mobile-menu-btn {
        color: rgba(255, 255, 255, 0.9);
        padding: 0.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .mobile-menu-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    .mobile-menu {
        background: white;
        border-top: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .mobile-nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: #374151;
        text-decoration: none;
        font-size: 0.875rem;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }

    .mobile-nav-link:hover {
        background-color: #f9fafb;
        color: #111827;
        padding-left: 1.5rem;
    }

    .mobile-nav-link.active {
        background-color: #eff6ff;
        color: #2563eb;
        border-left: 3px solid #2563eb;
    }

    .mobile-nav-link i {
        width: 1.25rem;
        color: #6b7280;
    }

    @media (max-width: 640px) {
        .nav-menu-links {
            display: none;
        }
    }
</style>

<nav x-data="{ open: false }" class="navbar-professional">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo and Main Navigation -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="logo-container shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <i class="fas fa-feather logo-icon"></i>
                        <span class="ml-3 text-white font-bold text-xl hidden sm:block" style="text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">
                            Smart-Harpia
                        </span>
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="nav-menu-links">
                    @if(request()->routeIs('pendente.index') || request()->routeIs('cadastro.index') || request()->routeIs('registro.index'))
                        @can('hasFullPermission', App\Models\AnilhaCadastro::class)
                        <div class="nav-menu-item">
                            <x-nav-link :href="route('cadastro')" :active="request()->routeIs('cadastro')" class="nav-menu-link">
                                <i class="fas fa-plus-circle"></i>
                                <span>{{ __('Cadastro') }}</span>
                            </x-nav-link>
                        </div>
                        @endcan
                        @can('hasFullPermission', App\Models\AnilhaPendente::class)
                        <div class="nav-menu-item">
                            <x-nav-link :href="route('pendente.index')" :active="request()->routeIs('pendente.index')" class="nav-menu-link">
                                <i class="fas fa-clock"></i>
                                <span>{{ __('Pendente') }}</span>
                            </x-nav-link>
                        </div>
                        @endcan
                        @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                        <div class="nav-menu-item">
                            <x-nav-link :href="route('registro.index')" :active="request()->routeIs('registro.index')" class="nav-menu-link">
                                <i class="fas fa-list-alt"></i>
                                <span>{{ __('Registro') }}</span>
                            </x-nav-link>
                        </div>
                        @endcan
                    @else
                        @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                        <div class="nav-menu-item">
                            <x-nav-link :href="route('registro.index')" class="nav-menu-link">
                                <i class="fas fa-tags"></i>
                                <span>{{ __('Smart-Anilhas') }}</span>
                            </x-nav-link>
                        </div>
                        @endcan
                        @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                        <div class="nav-menu-item">
                            <x-nav-link :href="route('smarthorta')" class="nav-menu-link">
                                <i class="fas fa-seedling"></i>
                                <span>{{ __('Smart-Horta') }}</span>
                            </x-nav-link>
                        </div>
                        @endcan
                        @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                        <div class="nav-menu-item">
                            <x-nav-link :href="route('macaddress')" class="nav-menu-link">
                                <i class="fas fa-network-wired"></i>
                                <span>{{ __('MacAddress') }}</span>
                            </x-nav-link>
                        </div>
                        @endcan
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 user-dropdown-container">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="user-dropdown-btn">
                            <img src="{{ asset('profile.jpeg') }}" alt="Avatar" class="user-avatar">
                            <span>Visitante</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="dropdown-content">
                            <a href="{{ route('home') }}" class="dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ __('Perfil') }}</span>
                            </a>
                            <a href="{{ route('home') }}" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>{{ __('Configurações') }}</span>
                            </a>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="dropdown-item logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>{{ __('Sair') }}</span>
                                </a>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu Button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="mobile-menu-btn">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>{{ __('Perfil') }}</span>
            </a>
            
            @if(request()->routeIs('pendente.index') || request()->routeIs('cadastro.index') || request()->routeIs('registro.index'))
                @can('hasFullPermission', App\Models\AnilhaCadastro::class)
                <a href="{{ route('cadastro') }}" class="mobile-nav-link {{ request()->routeIs('cadastro') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>{{ __('Cadastro') }}</span>
                </a>
                @endcan
                @can('hasFullPermission', App\Models\AnilhaPendente::class)
                <a href="{{ route('pendente.index') }}" class="mobile-nav-link {{ request()->routeIs('pendente.index') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span>{{ __('Pendente') }}</span>
                </a>
                @endcan
                @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                <a href="{{ route('registro.index') }}" class="mobile-nav-link {{ request()->routeIs('registro.index') ? 'active' : '' }}">
                    <i class="fas fa-list-alt"></i>
                    <span>{{ __('Registro') }}</span>
                </a>
                @endcan
            @else
                @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                <a href="{{ route('registro.index') }}" class="mobile-nav-link">
                    <i class="fas fa-tags"></i>
                    <span>{{ __('Smart-Anilhas') }}</span>
                </a>
                @endcan
                @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                <a href="{{ route('smarthorta') }}" class="mobile-nav-link">
                    <i class="fas fa-seedling"></i>
                    <span>{{ __('Smart-Horta') }}</span>
                </a>
                @endcan
                @can('hasFullPermission', App\Models\AnilhaRegistro::class)
                <a href="{{ route('macaddress') }}" class="mobile-nav-link">
                    <i class="fas fa-network-wired"></i>
                    <span>{{ __('MacAddress') }}</span>
                </a>
                @endcan
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4 pb-3">
                <img src="{{ asset('profile.jpeg') }}" alt="Avatar" class="user-avatar">
                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800">Visitante</div>
                    <div class="font-medium text-sm text-gray-500">visitante@email.com</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('home') }}" class="mobile-nav-link">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ __('Perfil') }}</span>
                </a>
                <a href="{{ route('home') }}" class="mobile-nav-link">
                    <i class="fas fa-cog"></i>
                    <span>{{ __('Configurações') }}</span>
                </a>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="mobile-nav-link" style="color: #dc2626;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>{{ __('Sair') }}</span>
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
