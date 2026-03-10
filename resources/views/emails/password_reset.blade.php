<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Estilos base para compatibilidad con clientes de correo */
        .body-bg {
            background-color: #0b111b !important;
            padding: 40px 10px;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
        }

        .main-card {
            max-width: 550px;
            margin: 0 auto;
            background-color: #111827;
            border: 1px solid #1f2937;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        .header-gradient {
            /* Fallback de color sólido para clientes que no soportan linear-gradient */
            background-color: #8a2be2;
            background: linear-gradient(135deg, #8a2be2, #00d4ff);
            padding: 35px 20px;
            text-align: center;
        }

        .content {
            padding: 45px 35px;
            text-align: center;
        }

        .h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .p {
            color: #9ca3af;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .btn-link {
            display: inline-block;
            padding: 16px 40px;
            background-color: #00d4ff;
            /* Fallback */
            background: linear-gradient(135deg, #8a2be2, #00d4ff);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 20px rgba(0, 212, 255, 0.2);
        }

        .footer-note {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
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
            <h2 style="margin:0; color:#ffffff; letter-spacing:4px; font-size:22px; font-weight:800;">SOFTWARE TECH</h2>
        </div>

        <div class="content">
            <h1 class="h1">ACTUALIZACIÓN DE IDENTIDAD</h1>

            <p class="p">
                Hola, <strong style="color: #ffffff;">{{ $name }}</strong>.<br>
                Se ha generado una solicitud para restablecer tus credenciales de acceso al
                <span class="brand-text">Innovation Lab</span>.
            </p>

            <div style="margin: 35px 0;">
                <a href="{{ $url }}" class="btn-link">Autorizar Cambio</a>
            </div>

            <p class="p" style="margin-top: 30px; font-size: 14px;">
                Por motivos de seguridad, este enlace expirará automáticamente en <strong>60 minutos</strong>. Si no
                solicitaste este cambio, puedes ignorar este mensaje.
            </p>

            <div class="footer-note">
                <p style="margin-bottom: 5px;">Este es un mensaje automático del protocolo de seguridad de Software
                    Tech.</p>
                <p style="margin-top: 0;">© 2026 Software Technologies. Identidad Protegida.</p>
            </div>
        </div>
    </div>
</body>

</html>