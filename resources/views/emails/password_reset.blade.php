<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        /* Estilos base para compatibilidad con clientes de correo */
        .body-bg {
            background-color: #0b111b;
            /* Azul muy oscuro de tu app */
            padding: 40px 10px;
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
        }

        .main-card {
            max-width: 550px;
            margin: 0 auto;
            background: #111827;
            /* Fondo de las tarjetas en tu app */
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        .header-gradient {
            background: linear-gradient(135deg, #8a2be2, #00d4ff);
            padding: 30px;
            text-align: center;
        }

        .content {
            padding: 40px 30px;
            text-align: center;
        }

        .h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 20px;
            letter-spacing: 1px;
        }

        .p {
            color: #9ca3af;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .btn-link {
            display: inline-block;
            padding: 15px 35px;
            background: linear-gradient(135deg, #8a2be2, #00d4ff);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-note {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: #6b7280;
            font-size: 12px;
        }

        .brand-text {
            color: #00d4ff;
            font-weight: bold;
        }
    </style>
</head>

<body class="body-bg">
    <div class="main-card">
        <div class="header-gradient">
            <h2 style="margin:0; color:white; letter-spacing:4px; font-size:20px;">SOFTWARE TECH</h2>
        </div>

        <div class="content">
            <h1 class="h1">ACTUALIZACIÓN DE IDENTIDAD</h1>

            <p class="p">
                Hola, <span style="color: #ffffff;">{{ $name }}</span>.<br>
                Se ha generado una solicitud para restablecer tus credenciales de acceso al
                <span class="brand-text">Innovation Lab</span>.
            </p>

            <a href="{{ $url }}" class="btn-link">Autorizar Cambio</a>

            <p class="p" style="margin-top: 30px; font-size: 14px;">
                Por motivos de seguridad, este enlace expirará automáticamente en <strong>60 minutos</strong>.
            </p>

            <div class="footer-note">
                <p>Este es un mensaje automático del protocolo de seguridad de Software Tech.</p>
                <p>© 2026 Software Technologies. Identidad Protegida.</p>
            </div>
        </div>
    </div>
</body>

</html>