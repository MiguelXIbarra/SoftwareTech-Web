<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Software Tech | Innovation Lab</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,700,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ELIMINAMOS EL FONDO NEGRO SÓLIDO Y USAMOS LA IMAGEN DIRECTAMENTE */
        body,
        html {
            height: 100vh;
            margin: 0;
            background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2000&auto=format&fit=crop') !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
            background-attachment: fixed !important;
            color: #fff;
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* --- SCROLLBAR GRADIENTE TECH --- */
        ::-webkit-scrollbar {
            width: 8px;
            background: #000;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.4);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #8a2be2, #00d4ff);
            border-radius: 10px;
        }

        /* CAPA DE OSCURIDAD MÍNIMA: Como la que se ve en tu captura de éxito */
        .auth-overlay-fix {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        .auth-content {
            position: relative;
            z-index: 2;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* CRISTAL AHUMADO: Ajustado para que brille como en tu dashboard */
        .glass-terminal-v2 {
            background: rgba(10, 10, 10, 0.8) !important;
            backdrop-filter: blur(25px) saturate(150%);
            -webkit-backdrop-filter: blur(25px) saturate(150%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 50px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.9);
        }

        .form-control-tech {
            background: rgba(20, 20, 20, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 12px;
            color: #fff !important;
            padding: 12px 45px 12px 15px !important;
            height: 50px;
        }

        .form-control-tech:focus {
            border-color: #00d4ff !important;
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
            outline: none;
        }

        /* CONTENEDOR DE CONTRASEÑA CON POSICIONAMIENTO RELATIVO */
        .password-container {
            position: relative !important;
            display: block !important;
            width: 100% !important;
        }

        /* ICONO DEL OJO POSICIONADO CORRECTAMENTE */
        .toggle-password {
            position: absolute !important;
            right: 15px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            cursor: pointer !important;
            color: rgba(255, 255, 255, 0.3) !important;
            z-index: 10 !important;
            font-size: 0.85rem !important;
            transition: all 0.3s ease !important;
            padding: 5px !important;
            user-select: none !important;
        }

        .toggle-password:hover {
            color: #ffffff !important;
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.2)) !important;
        }

        /* ESTADOS DEL TOGGLE */
        .toggle-password.visible {
            /* Sin color especial, mantiene el gris original */
        }

        /* FOOTER NEÓN: Idéntico al de tu app.blade.php */
        .main-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #000 !important;
            border-top: 1px solid transparent !important;
            border-image: linear-gradient(to right, transparent, #8a2be2, #00d4ff, #8a2be2, transparent) 1 !important;
            padding: 20px 30px;
            text-align: center;
            color: rgba(200, 200, 200, 0.8);
            font-size: 0.85rem;
            letter-spacing: 1px;
            z-index: 3;
        }

        .main-footer strong {
            color: #f8f9fa !important;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 800;
        }
    </style>
</head>

<body>
    <div class="auth-overlay-fix"></div>

    <div class="auth-content">
        @yield('content')
    </div>

    <footer class="main-footer">
        © 2026 <strong>Software Tech</strong> | Innovation Lab | v4.5
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para manejar el toggle de contraseña
            function togglePassword(icon) {
                const input = icon.parentElement.querySelector('input');
                if (input) {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                }
            }

            // Manejar TODOS los toggles de contraseña
            document.querySelectorAll('.toggle-password').forEach(icon => {
                icon.addEventListener('click', function() {
                    togglePassword(this);
                });
            });
        });
    </script>
</body>

</html>