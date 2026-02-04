<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SMART HARPIA - Sistema Inteligente de Monitoramento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0f766e;
            --primary-dark: #0d9488;
            --primary-light: #14b8a6;
            --sidebar-bg: #0c4a6e;
            --sidebar-text: rgba(255,255,255,0.9);
            --sidebar-hover: rgba(255,255,255,0.12);
            --card-shadow: 0 4px 24px rgba(15, 118, 110, 0.08);
            --card-hover: 0 12px 40px rgba(15, 118, 110, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* ========== NAVBAR ========== */
        .navbar-custom {
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-custom .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
        }

        .navbar-custom .navbar-brand img {
            height: 40px;
            width: auto;
        }

        .navbar-custom .nav-link {
            color: #475569;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: color 0.2s, background 0.2s;
        }

        .navbar-custom .nav-link:hover {
            color: var(--primary);
            background: rgba(15, 118, 110, 0.08);
        }

        .navbar-custom .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
        }

        .navbar-custom .btn-login:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(15, 118, 110, 0.35);
        }

        .navbar-custom .btn-login.btn-sm {
            padding: 0.4rem 0.9rem;
            font-size: 0.9rem;
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 2px rgba(15, 118, 110, 0.3);
        }

        /* ========== SIDEBAR (overlay em mobile) ========== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1050;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-header .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .sidebar-header .brand img {
            height: 36px;
        }

        .sidebar-close {
            background: none;
            border: none;
            color: rgba(255,255,255,0.8);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem;
            line-height: 1;
        }

        .sidebar-close:hover {
            color: #fff;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav .nav-item {
            list-style: none;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1.25rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }

        .sidebar-nav .nav-link i.sidebar-icon {
            width: 1.25rem;
            min-width: 1.25rem;
            text-align: center;
            font-size: 1rem;
            opacity: 0.9;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-nav .nav-link.nav-link--active {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-nav .nav-section-toggle {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.75rem 1.25rem;
            border: none;
            background: transparent;
            color: var(--sidebar-text);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            text-align: left;
            font-family: inherit;
            transition: background 0.2s, color 0.2s;
        }

        .sidebar-nav .nav-section-toggle:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-nav .nav-section-toggle.nav-section-toggle--active {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-nav .nav-section-toggle .nav-section-toggle-label {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .sidebar-nav .nav-section-toggle i.sidebar-icon {
            width: 1.25rem;
            min-width: 1.25rem;
            text-align: center;
            font-size: 1rem;
            opacity: 0.9;
        }

        .sidebar-nav .nav-section-toggle .sidebar-chevron {
            flex-shrink: 0;
            margin-left: 0.5rem;
            font-size: 0.65rem;
            transition: transform 0.25s ease;
        }

        .sidebar-nav .nav-section-toggle[aria-expanded="true"] .sidebar-chevron {
            transform: rotate(180deg);
        }

        .sidebar-nav .sidebar-subsistemas {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease;
        }

        .sidebar-nav .sidebar-subsistemas.open {
            max-height: 400px;
        }

        .sidebar-nav .nav-link.subsistema {
            padding: 0.5rem 1.25rem 0.5rem 2.5rem;
            font-size: 0.875rem;
        }

        .sidebar-nav .nav-link.subsistema i.sidebar-icon {
            font-size: 0.9rem;
        }

        .sidebar-nav .sidebar-anilhas {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.25s ease;
        }

        .sidebar-nav .sidebar-anilhas.open {
            max-height: 180px;
        }

        .sidebar-nav .nav-section-toggle.nav-subsection-toggle {
            padding: 0.45rem 1.25rem 0.45rem 2.5rem;
            font-size: 0.85rem;
        }

        .sidebar-nav .nav-link.subsistema-anilha {
            padding: 0.45rem 1.25rem 0.45rem 3.25rem;
            font-size: 0.825rem;
        }

        .sidebar-desktop .nav-link.subsistema-anilha {
            padding: 0.45rem 1.5rem 0.45rem 3.25rem;
        }

        /* ========== LAYOUT PRINCIPAL ========== */
        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar-desktop {
            display: none;
            width: 260px;
            flex-shrink: 0;
            min-height: 100vh;
            background: var(--sidebar-bg);
            padding: 1.5rem 0;
            box-shadow: 2px 0 12px rgba(0,0,0,0.06);
            flex-direction: column;
        }

        @media (min-width: 992px) {
            .sidebar-desktop {
                display: flex;
            }
        }

        .right-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar-desktop .brand {
            padding: 0 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
        }

        .sidebar-desktop .brand img {
            height: 34px;
        }

        .sidebar-desktop .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 500;
            margin: 0 0.75rem;
            border-radius: 10px;
            transition: background 0.2s;
        }

        .sidebar-desktop .nav-link i {
            width: 22px;
            text-align: center;
        }

        .sidebar-desktop .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-desktop .nav-section-toggle {
            padding: 0.75rem 1.5rem;
        }

        .sidebar-desktop .nav-section-toggle.nav-section-toggle--active {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-desktop .nav-link.subsistema {
            padding: 0.5rem 1.5rem 0.5rem 2.5rem;
        }

        .page-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 2rem 1.5rem 3rem;
        }

        @media (min-width: 768px) {
            .page-content {
                padding: 2.5rem 2rem 4rem;
            }
        }

        /* ========== HERO ========== */
        .hero {
            background: linear-gradient(135deg, #0f766e 0%, #0c4a6e 100%);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            color: #fff;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero h1 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .hero p {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 480px;
        }

        .hero .btn-cta {
            margin-top: 1.5rem;
            background: #fff;
            color: var(--primary);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .hero .btn-cta:hover {
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }

        @media (min-width: 768px) {
            .hero {
                padding: 3rem 2.5rem;
            }
            .hero h1 {
                font-size: 2.25rem;
            }
        }

        /* ========== CARDS ========== */
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary);
        }

        .cards-grid {
            display: grid;
            gap: 1.25rem;
            grid-template-columns: 1fr;
        }

        @media (min-width: 576px) {
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 992px) {
            .cards-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .card-custom {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(15, 118, 110, 0.06);
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-hover);
        }

        .card-custom .icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }

        .card-custom .icon-wrap.teal {
            background: rgba(20, 184, 166, 0.15);
            color: var(--primary);
        }

        .card-custom .icon-wrap.blue {
            background: rgba(14, 165, 233, 0.15);
            color: #0284c7;
        }

        .card-custom .icon-wrap.amber {
            background: rgba(245, 158, 11, 0.15);
            color: #d97706;
        }

        .card-custom .icon-wrap.violet {
            background: rgba(139, 92, 246, 0.15);
            color: #7c3aed;
        }

        .card-custom .icon-wrap.emerald {
            background: rgba(16, 185, 129, 0.15);
            color: #059669;
        }

        .card-custom .icon-wrap.rose {
            background: rgba(244, 63, 94, 0.15);
            color: #e11d48;
        }

        .card-custom.card-link {
            text-decoration: none;
            color: inherit;
        }

        .card-custom.card-link:hover {
            color: inherit;
        }

        .card-custom h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.35rem;
        }

        .card-custom p {
            font-size: 0.9rem;
            color: #64748b;
            margin: 0;
            line-height: 1.5;
        }

        /* ========== FOOTER ========== */
        .site-footer {
            margin-top: auto;
            padding: 1.5rem 0;
            border-top: 1px solid #e2e8f0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .site-footer .incubadora-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .site-footer .incubadora-link:hover {
            color: var(--primary);
        }

        .site-footer .incubadora-link img {
            max-height: 44px;
            width: auto;
        }

        .site-footer .copy {
            font-size: 0.85rem;
            color: #94a3b8;
        }

        /* ========== CONTEÚDO DINÂMICO (views) ========== */
        .content-view {
            display: none;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }

        .content-view.active {
            display: flex;
        }

        .view-subsistema-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            margin-bottom: 0.5rem;
            flex-shrink: 0;
        }

        .view-subsistema-header .btn-voltar {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.75rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }

        .view-subsistema-header .btn-voltar:hover {
            background: var(--primary-dark);
            color: #fff;
        }

        .view-subsistema-iframe-wrap {
            flex: 1;
            min-height: 400px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: #fff;
        }

        .view-subsistema-iframe-wrap iframe {
            width: 100%;
            height: 100%;
            min-height: 500px;
            border: none;
        }

        .recursos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1rem;
        }

        .recursos-item {
            background: #fff;
            border-radius: 14px;
            padding: 1.25rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(15, 118, 110, 0.06);
        }

        .recursos-item i {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .recursos-item h4 {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .recursos-item p {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }

        .modal-em-desenvolvimento {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1060;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.4);
            padding: 1rem;
        }

        .modal-em-desenvolvimento.show {
            display: flex;
        }

        .modal-em-desenvolvimento .modal-content {
            background: #fff;
            border-radius: 16px;
            padding: 1.75rem;
            max-width: 360px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }

        .modal-em-desenvolvimento .modal-content i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .modal-em-desenvolvimento .modal-content h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .modal-em-desenvolvimento .modal-content p {
            font-size: 0.95rem;
            color: #64748b;
            margin-bottom: 1.25rem;
        }

        .modal-em-desenvolvimento .btn-fechar {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .modal-em-desenvolvimento .btn-fechar:hover {
            background: var(--primary-dark);
            color: #fff;
        }
    </style>
</head>
<body data-sistemas-active="{{ request()->routeIs('phmetro', 'macaddress', 'smarthorta', 'gpstartarugas', 'cadastro', 'registro', 'pendente') ? '1' : '0' }}">
    <!-- Modal Em desenvolvimento -->
    <div class="modal-em-desenvolvimento" id="modalEmDesenvolvimento" aria-hidden="true">
        <div class="modal-content">
            <i class="fas fa-tools"></i>
            <h3>Em desenvolvimento</h3>
            <p>Esta funcionalidade está em desenvolvimento e estará disponível em breve.</p>
            <button type="button" class="btn-fechar" id="modalEmDesenvolvimentoFechar">OK</button>
        </div>
    </div>

    <!-- Overlay sidebar (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

    <!-- Sidebar mobile -->
    <aside class="sidebar" id="sidebar" aria-label="Menu lateral">
        <div class="sidebar-header">
            <div class="brand">
                <img src="{{ asset('logo.png') }}" alt="Smart Harpia">
                <span>SMART HARPIA</span>
            </div>
            <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Fechar menu">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link js-sidebar-view {{ (request()->path() === '' || request()->path() === '/') ? 'nav-link--active' : '' }}" href="#" data-view="inicio"><i class="fas fa-home sidebar-icon"></i> Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-sidebar-view" href="#" data-view="recursos"><i class="fas fa-th-large sidebar-icon"></i> Recursos</a>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-section-toggle sidebar-sistemas-toggle js-sidebar-view-sistemas" aria-expanded="false" aria-controls="sidebarSistemasListMobile" data-view="sistemas">
                        <span class="nav-section-toggle-label">
                            <i class="fas fa-cubes sidebar-icon"></i>
                            <span>Sistemas</span>
                        </span>
                        <i class="fas fa-chevron-down sidebar-chevron"></i>
                    </button>
                    <div class="sidebar-subsistemas" id="sidebarSistemasListMobile">
                        <a class="nav-link subsistema js-load-subsistema-sidebar" href="#" data-url="{{ route('phmetro') }}" data-title="PHmetro"><i class="fas fa-vial sidebar-icon"></i> PHmetro</a>
                        <a class="nav-link subsistema js-load-subsistema-sidebar" href="#" data-url="{{ route('macaddress') }}" data-title="Mac Address"><i class="fas fa-network-wired sidebar-icon"></i> Mac Address</a>
                        <a class="nav-link subsistema js-load-subsistema-sidebar" href="#" data-url="{{ route('smarthorta') }}" data-title="Smart Horta"><i class="fas fa-seedling sidebar-icon"></i> Smart Horta</a>
                        <a class="nav-link subsistema js-load-subsistema-sidebar" href="#" data-url="{{ route('gpstartarugas') }}" data-title="GPS Tartarugas"><i class="fas fa-map-marker-alt sidebar-icon"></i> GPS Tartarugas</a>
                        <button type="button" class="nav-section-toggle nav-subsection-toggle sidebar-anilhas-toggle" aria-expanded="false" aria-controls="sidebarAnilhasListMobile">
                            <span class="nav-section-toggle-label">
                                <i class="fas fa-rss sidebar-icon"></i>
                                <span>Anilhas</span>
                            </span>
                            <i class="fas fa-chevron-down sidebar-chevron"></i>
                        </button>
                        <div class="sidebar-anilhas" id="sidebarAnilhasListMobile">
                            <a class="nav-link subsistema-anilha js-load-subsistema-sidebar" href="#" data-url="{{ route('cadastro') }}" data-title="Cadastro Anilhas"><i class="fas fa-list-check sidebar-icon"></i> Cadastro</a>
                            <a class="nav-link subsistema-anilha js-load-subsistema-sidebar" href="#" data-url="{{ route('registro') }}" data-title="Registro Anilhas"><i class="fas fa-clipboard-list sidebar-icon"></i> Registro</a>
                            <a class="nav-link subsistema-anilha js-load-subsistema-sidebar" href="#" data-url="{{ route('pendente') }}" data-title="Pendente Anilhas"><i class="fas fa-clock sidebar-icon"></i> Pendente</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-btn-entrar" href="#"><i class="fas fa-lock sidebar-icon"></i> Entrar</a>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="layout-wrapper">
        <!-- Sidebar desktop (do topo ao fundo) -->
        <aside class="sidebar-desktop" aria-label="Navegação lateral">
            <div class="brand">
                <img src="{{ asset('logo.png') }}" alt="Smart Harpia">
                <span>SMART HARPIA</span>
            </div>
            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link js-sidebar-view {{ (request()->path() === '' || request()->path() === '/') ? 'nav-link--active' : '' }}" href="#" data-view="inicio"><i class="fas fa-home sidebar-icon"></i> Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-sidebar-view" href="#" data-view="recursos"><i class="fas fa-th-large sidebar-icon"></i> Recursos</a>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-section-toggle sidebar-sistemas-toggle js-sidebar-view-sistemas" aria-expanded="false" aria-controls="sidebarSistemasList" data-view="sistemas">
                            <span class="nav-section-toggle-label">
                                <i class="fas fa-cubes sidebar-icon"></i>
                                <span>Sistemas</span>
                            </span>
                            <i class="fas fa-chevron-down sidebar-chevron"></i>
                        </button>
                        <div class="sidebar-subsistemas" id="sidebarSistemasList">
                            <a class="nav-link subsistema js-load-subsistema-sidebar" href="#" data-url="{{ route('phmetro') }}" data-title="PHmetro"><i class="fas fa-vial sidebar-icon"></i> PHmetro</a>
                            <a class="nav-link subsistema js-load-subsistema-sidebar" href="#" data-url="{{ route('macaddress') }}" data-title="Mac Address"><i class="fas fa-network-wired sidebar-icon"></i> Mac Address</a>
                            <a class="nav-link subsistema js-load-subsistema-sidebar" href="#" data-url="{{ route('smarthorta') }}" data-title="Smart Horta"><i class="fas fa-seedling sidebar-icon"></i> Smart Horta</a>
                            <a class="nav-link subsistema js-load-subsistema-sidebar" href="#" data-url="{{ route('gpstartarugas') }}" data-title="GPS Tartarugas"><i class="fas fa-map-marker-alt sidebar-icon"></i> GPS Tartarugas</a>
                            <button type="button" class="nav-section-toggle nav-subsection-toggle sidebar-anilhas-toggle" aria-expanded="false" aria-controls="sidebarAnilhasList">
                                <span class="nav-section-toggle-label">
                                    <i class="fas fa-rss sidebar-icon"></i>
                                    <span>Anilhas</span>
                                </span>
                                <i class="fas fa-chevron-down sidebar-chevron"></i>
                            </button>
                            <div class="sidebar-anilhas" id="sidebarAnilhasList">
                                <a class="nav-link subsistema-anilha js-load-subsistema-sidebar" href="#" data-url="{{ route('cadastro') }}" data-title="Cadastro Anilhas"><i class="fas fa-list-check sidebar-icon"></i> Cadastro</a>
                                <a class="nav-link subsistema-anilha js-load-subsistema-sidebar" href="#" data-url="{{ route('registro') }}" data-title="Registro Anilhas"><i class="fas fa-clipboard-list sidebar-icon"></i> Registro</a>
                                <a class="nav-link subsistema-anilha js-load-subsistema-sidebar" href="#" data-url="{{ route('pendente') }}" data-title="Pendente Anilhas"><i class="fas fa-clock sidebar-icon"></i> Pendente</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Coluna direita: navbar + conteúdo -->
        <div class="right-column">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-custom">
                <div class="container-fluid">
                    <div class="d-none d-lg-flex align-items-center ms-auto">
                        <a href="#" class="btn btn-login js-btn-entrar">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Entrar</span>
                        </a>
                    </div>
                    <div class="d-lg-none ms-auto d-flex align-items-center gap-2">
                        <a href="#" class="btn btn-login btn-sm js-btn-entrar">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Entrar</span>
                        </a>
                        <button class="navbar-toggler" type="button" id="sidebarToggler" aria-label="Abrir menu">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Conteúdo dinâmico -->
            <main class="page-content">
                <!-- View Início -->
                <div class="content-view active" id="view-inicio" data-view="inicio">
                    <section class="hero">
                        <h1><i class="fas fa-feather-alt me-2"></i> SMART HARPIA</h1>
                        <p>Sistema Inteligente de Monitoramento com Anilhas RFID. Controle e rastreabilidade para sua operação.</p>
                        <a href="#" class="btn-cta js-show-view" data-view="sistemas">
                            <i class="fas fa-arrow-right"></i>
                            Acessar sistemas
                        </a>
                    </section>
                    <div class="mb-4">
                        <h2 class="section-title"><i class="fas fa-info-circle"></i> Informações gerais</h2>
                        <p style="color: #64748b; line-height: 1.6; max-width: 640px;">Bem-vindo ao Smart Harpia. Aqui você pode acessar os recursos do sistema, consultar os módulos disponíveis (PHmetro, Mac Address, Smart Horta, Anilhas) e visualizar os dados de cada tabela sem sair desta página. Use o menu ao lado para navegar.</p>
                    </div>
                    <footer class="site-footer" style="margin-top: auto;">
                        <a href="https://incubadoraifpr.com.br" target="_blank" rel="noopener" class="incubadora-link">
                            <img src="{{ asset('incubadora.png') }}" alt="Incubadora IFPR">
                            <span>Incubadora IFPR</span>
                        </a>
                        <span class="copy">&copy; {{ date('Y') }} Smart Harpia.</span>
                    </footer>
                </div>

                <!-- View Recursos -->
                <div class="content-view" id="view-recursos" data-view="recursos">
                    <h2 class="section-title"><i class="fas fa-th-large"></i> Recursos</h2>
                    <div class="recursos-grid">
                        <div class="recursos-item">
                            <i class="fas fa-rss"></i>
                            <h4>Anilhas RFID</h4>
                            <p>Identificação por radiofrequência e gestão de cadastros.</p>
                        </div>
                        <div class="recursos-item">
                            <i class="fas fa-chart-line"></i>
                            <h4>Monitoramento</h4>
                            <p>Acompanhamento em tempo real e indicadores.</p>
                        </div>
                        <div class="recursos-item">
                            <i class="fas fa-database"></i>
                            <h4>Dados e tabelas</h4>
                            <p>Consulta às tabelas de cada subsistema integrado.</p>
                        </div>
                        <div class="recursos-item">
                            <i class="fas fa-shield-alt"></i>
                            <h4>Segurança</h4>
                            <p>Acesso controlado e autenticação.</p>
                        </div>
                    </div>
                    <footer class="site-footer" style="margin-top: auto;">
                        <a href="https://incubadoraifpr.com.br" target="_blank" rel="noopener" class="incubadora-link">
                            <img src="{{ asset('incubadora.png') }}" alt="Incubadora IFPR">
                            <span>Incubadora IFPR</span>
                        </a>
                        <span class="copy">&copy; {{ date('Y') }} Smart Harpia.</span>
                    </footer>
                </div>

                <!-- View Sistemas (lista de opções) -->
                <div class="content-view" id="view-sistemas" data-view="sistemas">
                    <h2 class="section-title"><i class="fas fa-layer-group"></i> Sistemas</h2>
                    <p style="color: #64748b; margin-bottom: 1.5rem;">Clique em um sistema para abrir a tela de dados no quadro abaixo.</p>

                    <h3 class="section-title" style="font-size: 1.05rem; margin-top: 1.5rem; margin-bottom: 0.75rem;"><i class="fas fa-rss"></i> Anilhas</h3>
                    <div class="cards-grid mb-4">
                        <a href="#" class="card-custom card-link js-load-subsistema" data-url="{{ route('cadastro') }}" data-title="Cadastro Anilhas">
                            <div class="icon-wrap amber"><i class="fas fa-list-check"></i></div>
                            <h3>Cadastro</h3>
                            <p>Cadastro e gestão de anilhas RFID. Tabela de cadastros.</p>
                        </a>
                        <a href="#" class="card-custom card-link js-load-subsistema" data-url="{{ route('registro') }}" data-title="Registro Anilhas">
                            <div class="icon-wrap violet"><i class="fas fa-clipboard-list"></i></div>
                            <h3>Registro</h3>
                            <p>Registros de anilhas. Histórico e dados da tabela de registros.</p>
                        </a>
                        <a href="#" class="card-custom card-link js-load-subsistema" data-url="{{ route('pendente') }}" data-title="Pendente Anilhas">
                            <div class="icon-wrap rose"><i class="fas fa-clock"></i></div>
                            <h3>Pendente</h3>
                            <p>Anilhas pendentes. Aprovações e itens aguardando tratamento.</p>
                        </a>
                    </div>

                    <h3 class="section-title" style="font-size: 1.05rem; margin-top: 1rem; margin-bottom: 0.75rem;"><i class="fas fa-cubes"></i> Outros sistemas</h3>
                    <div class="cards-grid">
                        <a href="#" class="card-custom card-link js-load-subsistema" data-url="{{ route('gpstartarugas') }}" data-title="GPS Tartarugas">
                            <div class="icon-wrap teal"><i class="fas fa-map-marker-alt"></i></div>
                            <h3>GPS Tartarugas</h3>
                            <p>Rastreamento GPS de tartarugas. Localização e histórico.</p>
                        </a>
                        <a href="#" class="card-custom card-link js-load-subsistema" data-url="{{ route('phmetro') }}" data-title="PHmetro">
                            <div class="icon-wrap teal"><i class="fas fa-vial"></i></div>
                            <h3>PHmetro</h3>
                            <p>Dados do medidor de pH. Visualize e acompanhe as leituras.</p>
                        </a>
                        <a href="#" class="card-custom card-link js-load-subsistema" data-url="{{ route('macaddress') }}" data-title="Mac Address">
                            <div class="icon-wrap blue"><i class="fas fa-network-wired"></i></div>
                            <h3>Mac Address</h3>
                            <p>Gestão de endereços MAC. Consulte a tabela de dispositivos.</p>
                        </a>
                        <a href="#" class="card-custom card-link js-load-subsistema" data-url="{{ route('smarthorta') }}" data-title="Smart Horta">
                            <div class="icon-wrap emerald"><i class="fas fa-seedling"></i></div>
                            <h3>Smart Horta</h3>
                            <p>Monitoramento da horta inteligente. Dados e indicadores.</p>
                        </a>
                    </div>
                    <footer class="site-footer" style="margin-top: auto;">
                        <a href="https://incubadoraifpr.com.br" target="_blank" rel="noopener" class="incubadora-link">
                            <img src="{{ asset('incubadora.png') }}" alt="Incubadora IFPR">
                            <span>Incubadora IFPR</span>
                        </a>
                        <span class="copy">&copy; {{ date('Y') }} Smart Harpia.</span>
                    </footer>
                </div>

                <!-- View Subsistema (iframe com a tela do sistema) -->
                <div class="content-view" id="view-subsistema" data-view="subsistema">
                    <div class="view-subsistema-header">
                        <button type="button" class="btn-voltar js-show-view" data-view="sistemas">
                            <i class="fas fa-arrow-left"></i> Voltar aos sistemas
                        </button>
                        <span class="view-subsistema-title" id="viewSubsistemaTitle" style="font-weight: 600; color: #0f172a;"></span>
                    </div>
                    <div class="view-subsistema-iframe-wrap">
                        <iframe id="frameSubsistema" title="Subsistema"></iframe>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebarOverlay');
            var sidebarClose = document.getElementById('sidebarClose');
            var toggler = document.getElementById('sidebarToggler');
            var frameSubsistema = document.getElementById('frameSubsistema');
            var viewSubsistemaTitle = document.getElementById('viewSubsistemaTitle');
            var currentView = 'inicio';

            function showView(viewId) {
                currentView = viewId;
                document.querySelectorAll('.content-view').forEach(function(el) {
                    el.classList.toggle('active', el.dataset.view === viewId);
                });
                document.querySelectorAll('.js-sidebar-view').forEach(function(link) {
                    var v = link.getAttribute('data-view');
                    link.classList.toggle('nav-link--active', v === viewId);
                });
                document.querySelectorAll('.nav-section-toggle').forEach(function(btn) {
                    var isSistemas = viewId === 'sistemas' || viewId === 'subsistema';
                    btn.classList.toggle('nav-section-toggle--active', isSistemas);
                });
            }

            function loadSubsistema(url, title) {
                if (frameSubsistema) frameSubsistema.src = url;
                if (viewSubsistemaTitle) viewSubsistemaTitle.textContent = title || '';
                showView('subsistema');
            }

            document.querySelectorAll('.js-show-view').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    var v = this.getAttribute('data-view');
                    if (v) showView(v);
                });
            });

            document.querySelectorAll('.js-sidebar-view').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    var v = this.getAttribute('data-view');
                    if (v) showView(v);
                    if (window.innerWidth < 992 && sidebar) {
                        sidebar.classList.remove('open');
                        if (overlay) overlay.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                });
            });

            document.querySelectorAll('.js-sidebar-view-sistemas').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var list = this.nextElementSibling;
                    if (list && list.classList.contains('sidebar-subsistemas')) {
                        var isOpen = list.classList.toggle('open');
                        this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    }
                    var v = this.getAttribute('data-view');
                    if (v) showView(v);
                });
            });

            document.querySelectorAll('.js-load-subsistema, .js-load-subsistema-sidebar').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    var url = this.getAttribute('data-url');
                    var title = this.getAttribute('data-title');
                    if (url) loadSubsistema(url + (url.indexOf('?') >= 0 ? '&' : '?') + 'embed=1', title);
                    if (window.innerWidth < 992 && sidebar) {
                        sidebar.classList.remove('open');
                        if (overlay) overlay.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                });
            });

            function openSidebar() {
                if (!sidebar || !overlay) return;
                sidebar.classList.add('open');
                overlay.classList.add('show');
                overlay.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (!sidebar || !overlay) return;
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                overlay.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            var modalEmDev = document.getElementById('modalEmDesenvolvimento');
            var modalEmDevFechar = document.getElementById('modalEmDesenvolvimentoFechar');
            document.querySelectorAll('.js-btn-entrar').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (modalEmDev) {
                        modalEmDev.classList.add('show');
                        modalEmDev.setAttribute('aria-hidden', 'false');
                    }
                    if (window.innerWidth < 992 && sidebar) closeSidebar();
                });
            });
            function closeModalEmDev() {
                if (modalEmDev) {
                    modalEmDev.classList.remove('show');
                    modalEmDev.setAttribute('aria-hidden', 'true');
                }
            }
            if (modalEmDevFechar) modalEmDevFechar.addEventListener('click', closeModalEmDev);
            if (modalEmDev) modalEmDev.addEventListener('click', function(e) { if (e.target === modalEmDev) closeModalEmDev(); });

            if (toggler) toggler.addEventListener('click', function() { if (window.innerWidth < 992) openSidebar(); });
            if (overlay) overlay.addEventListener('click', closeSidebar);
            if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);

            document.querySelectorAll('.sidebar .nav-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) closeSidebar();
                });
            });

            document.querySelectorAll('.nav-section-toggle').forEach(function(btn) {
                if (btn.classList.contains('js-sidebar-view-sistemas')) return;
                btn.addEventListener('click', function() {
                    var list = this.nextElementSibling;
                    if (!list) return;
                    if (list.classList.contains('sidebar-subsistemas')) {
                        var isOpen = list.classList.toggle('open');
                        this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    } else if (list.classList.contains('sidebar-anilhas')) {
                        var isOpen = list.classList.toggle('open');
                        this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    }
                });
            });
        })();
    </script>
</body>
</html>
