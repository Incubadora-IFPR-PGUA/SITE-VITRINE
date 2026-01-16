@extends('index')

@section('conteudo')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Welcome Section -->
    <!-- <div class="welcome-section">
        <div class="container">
            <div class="welcome-content text-center">
                <h1 class="welcome-title">
                    Seja bem-vindo(a)
                    <span class="welcome-name">Visitante</span>
                </h1>
                <p class="welcome-subtitle">ao Sistema Smart Harpia!</p>
                <div class="welcome-divider"></div>
            </div>
        </div>
    </div> -->

    <!-- Cards Section -->
    <div class="cards-section">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <!-- Card 1 - Smart Anilhas -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <a href="{{ route('registro') }}" class="card-modern-link">
                        <div class="card-modern">
                            <div class="card-image-wrapper">
                                <img src="https://png.pngtree.com/thumb_back/fh260/background/20220217/pngtree-organic-poultry-freerange-chickens-on-a-farm-in-germany-photo-image_35385422.jpg" 
                                     class="card-image" alt="Smart Anilhas">
                                <div class="card-overlay">
                                    <i class="fas fa-dove card-icon"></i>
                                </div>
                            </div>
                            <div class="card-content">
                                <h5 class="card-title-modern">
                                    <i class="fas fa-feather-alt me-2"></i>
                                    SMART ANILHAS
                                </h5>
                                <p class="card-description">Sistema desenvolvido com objetivo de monitorar as aves!</p>
                                <div class="card-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Card 2 - Smart Horta -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <a href="{{ url('/smarthorta') }}" class="card-modern-link">
                        <div class="card-modern">
                            <div class="card-image-wrapper">
                                <img src="https://s2.glbimg.com/r42vuL4vV9D55oBLl0CmO5Ht9VY=/512x320/smart/e.glbimg.com/og/ed/f/original/2022/01/18/tudo-o-que-voce-precisa-saber-para-ter-uma-horta-sustentavel-em-casa_3.jpg" 
                                     class="card-image" alt="Smart Horta">
                                <div class="card-overlay">
                                    <i class="fas fa-seedling card-icon"></i>
                                </div>
                            </div>
                            <div class="card-content">
                                <h5 class="card-title-modern">
                                    <i class="fas fa-leaf me-2"></i>
                                    SMART HORTA
                                </h5>
                                <p class="card-description">Sistema desenvolvido com objetivo de cuidar e monitorar as plantas!</p>
                                <div class="card-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Card 3 - Mac Address -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <a href="{{ url('/macaddress') }}" class="card-modern-link">
                        <div class="card-modern">
                            <div class="card-image-wrapper">
                                <img src="https://www.fao.org/images/nationalforestmonitoringlibraries/default-album/fao_25023_0034.jpg?sfvrsn=7fcb66fb_11" 
                                     class="card-image" alt="Mac Address">
                                <div class="card-overlay">
                                    <i class="fas fa-tree card-icon"></i>
                                </div>
                            </div>
                            <div class="card-content">
                                <h5 class="card-title-modern">
                                    <i class="fas fa-network-wired me-2"></i>
                                    MAC ADDRESS
                                </h5>
                                <p class="card-description">Tem como objetivo a monitoração de entradas não autorizadas em regiões ambientais preservadas!</p>
                                <div class="card-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Card 4 - PH Metro -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <a href="{{ url('/phmetro') }}" class="card-modern-link">
                        <div class="card-modern">
                            <div class="card-image-wrapper">
                                <img src="https://quimicaresponde.proec.ufabc.edu.br/wp-content/uploads/2016/01/tratamento-ph-agua.jpg" 
                                     class="card-image" alt="PH Metro">
                                <div class="card-overlay">
                                    <i class="fas fa-flask card-icon"></i>
                                </div>
                            </div>
                            <div class="card-content">
                                <h5 class="card-title-modern">
                                    <i class="fas fa-tint me-2"></i>
                                    PHMETRO
                                </h5>
                                <p class="card-description">Tem como objetivo a monitoração do ph em locais especificos onde haja corrente de água potável!</p>
                                <div class="card-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 20px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .welcome-name {
            background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
            margin-bottom: 20px;
        }

        .welcome-divider {
            width: 80px;
            height: 4px;
            background: white;
            margin: 20px auto;
            border-radius: 2px;
        }

        /* Cards Section */
        .cards-section {
            padding: 0 20px 60px;
        }

        .card-modern-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }

        .card-modern {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .card-modern:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 40px rgba(102, 126, 234, 0.3);
        }

        .card-image-wrapper {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .card-modern:hover .card-image {
            transform: scale(1.1);
        }

        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .card-modern:hover .card-overlay {
            opacity: 1;
        }

        .card-icon {
            font-size: 3rem;
            color: white;
            transform: scale(0.8);
            transition: transform 0.4s ease;
        }

        .card-modern:hover .card-icon {
            transform: scale(1);
        }

        .card-content {
            padding: 25px 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .card-title-modern {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .card-modern:hover .card-title-modern {
            color: #667eea;
        }

        .card-title-modern i {
            color: #667eea;
            font-size: 1.1rem;
        }

        .card-description {
            font-size: 0.95rem;
            color: #718096;
            line-height: 1.6;
            margin-bottom: 0;
            flex-grow: 1;
        }

        .card-arrow {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
            transform: translateX(10px);
            opacity: 0;
            transition: all 0.4s ease;
        }

        .card-modern:hover .card-arrow {
            transform: translateX(0);
            opacity: 1;
        }

        /* Responsividade */
        @media (max-width: 1199px) {
            .welcome-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 991px) {
            .welcome-section {
                padding: 50px 20px;
                margin-bottom: 40px;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .welcome-subtitle {
                font-size: 1.1rem;
            }

            .cards-section {
                padding: 0 15px 50px;
            }

            .card-image-wrapper {
                height: 200px;
            }
        }

        @media (max-width: 768px) {
            .welcome-section {
                padding: 40px 15px;
                margin-bottom: 30px;
            }

            .welcome-title {
                font-size: 1.75rem;
            }

            .welcome-subtitle {
                font-size: 1rem;
            }

            .cards-section {
                padding: 0 10px 40px;
            }

            .card-content {
                padding: 20px 18px;
            }

            .card-title-modern {
                font-size: 1.15rem;
            }

            .card-description {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .welcome-title {
                font-size: 1.5rem;
            }

            .card-image-wrapper {
                height: 180px;
            }

            .card-title-modern {
                font-size: 1.05rem;
            }
        }
    </style>

@endsection