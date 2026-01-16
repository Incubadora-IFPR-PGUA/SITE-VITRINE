<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SMART HARPIA - Sistema Inteligente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
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

        .main-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }

        .card-main {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px 30px;
            animation: slideUp 0.6s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            max-width: 180px;
            width: 100%;
            height: auto;
            margin-bottom: 20px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }

        .logo-container:hover img {
            transform: scale(1.05);
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 0.95rem;
            color: #6c757d;
            font-weight: 400;
            margin-bottom: 30px;
        }

        .login-button {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .login-button:hover::before {
            left: 100%;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
            color: white;
            text-decoration: none;
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button i {
            font-size: 1.2rem;
        }

        .footer-image {
            position: fixed;
            bottom: 20px;
            right: 20px;
            max-width: 120px;
            width: auto;
            height: auto;
            opacity: 0.7;
            transition: opacity 0.3s ease;
            z-index: 0;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.2));
        }

        .footer-image:hover {
            opacity: 1;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .main-container {
                padding: 15px;
                max-width: 100%;
            }

            .card-main {
                padding: 30px 20px;
                border-radius: 20px;
            }

            .brand-title {
                font-size: 1.75rem;
            }

            .brand-subtitle {
                font-size: 0.85rem;
            }

            .logo-container img {
                max-width: 150px;
            }

            .login-button {
                padding: 12px 20px;
                font-size: 1rem;
            }

            .footer-image {
                max-width: 100px;
                bottom: 15px;
                right: 15px;
            }
        }

        @media (max-width: 576px) {
            .card-main {
                padding: 25px 18px;
            }

            .brand-title {
                font-size: 1.5rem;
            }

            .logo-container img {
                max-width: 130px;
            }

            .footer-image {
                max-width: 80px;
            }
        }

        /* Animações de entrada */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .footer-image {
            animation: fadeIn 1s ease-out 0.3s backwards;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="card-main">
            <div class="logo-container">
                <img src="{{ asset('logo.png') }}" alt="Smart Harpia Logo" class="logo-img">
                <h1 class="brand-title">SMART HARPIA</h1>
                <p class="brand-subtitle">Sistema Inteligente de Monitoramento</p>
            </div>

            <a href="{{ route('login') }}" class="login-button">
                <i class="fas fa-sign-in-alt"></i>
                <span>Entrar no Sistema</span>
            </a>
        </div>
    </div>

    <img src="{{ asset('incubadora.png') }}" alt="Incubadora" class="footer-image">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>