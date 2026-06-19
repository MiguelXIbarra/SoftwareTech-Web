<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Sistema - Software Tech</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        html, body {
            background-color: #030712 !important;
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .portal-viewport {
            background: #030712 !important;
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 60px;
            position: relative;
        }

        .portal-viewport::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 15% 15%, rgba(6, 182, 212, 0.08) 0%, transparent 50%),
                        radial-gradient(circle at 85% 85%, rgba(138, 43, 226, 0.08) 0%, transparent 50%);
            z-index: 1;
            pointer-events: none;
        }

        .custom-portal-nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(3, 7, 18, 0.4) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            padding: 15px 0;
        }

        .portal-container {
            position: relative;
            z-index: 5;
            max-width: 1100px;
        }

        /* Cabecera sin fondos grises estorbosos */
        .portal-header-panel {
            background: rgba(0, 0, 0, 0.4) !important;
            border: 1px solid rgba(6, 182, 212, 0.15) !important;
            border-top-color: rgba(6, 182, 212, 0.3) !important;
            border-left-color: rgba(6, 182, 212, 0.3) !important;
            backdrop-filter: blur(24px) saturate(160%) !important;
            -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.05) !important;
            border-radius: 20px;
            padding: 30px 40px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .portal-user-tag {
            font-family: monospace;
            font-size: 0.75rem;
            color: #06b6d4;
            letter-spacing: 2px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .portal-welcome-title {
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin: 0;
        }

        /* Contenedores de Cristal Oscuro */
        .project-glass-card {
            background: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            border-top-color: rgba(255, 255, 255, 0.12) !important;
            border-left-color: rgba(255, 255, 255, 0.12) !important;
            backdrop-filter: blur(20px) saturate(160%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(160%) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5) !important;
            border-radius: 24px;
            padding: 40px;
        }

        .status-badge-active {
            font-family: monospace;
            font-size: 0.75rem;
            background: rgba(6, 182, 212, 0.05);
            border: 1px solid rgba(6, 182, 212, 0.2);
            color: #06b6d4;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 600;
        }

        /* Estilos de la lista de módulos/hitos */
        .modulo-item {
            background: rgba(255, 255, 255, 0.01);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .modulo-item:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(138, 43, 226, 0.2);
        }

        .btn-portal-back {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #ffffff !important;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-portal-back:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(6, 182, 212, 0.3);
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.15);
        }
    </style>
</head>
<body>

    <nav class="custom-portal-nav">
        <div class="container portal-container d-flex justify-content-between align-items-center">
            <a href="/" style="text-decoration: none;">
                <span class="text-white fw-bold" style="letter-spacing: 2px;">SOFTWARE TECH</span>
            </a>
            <span class="text-muted small font-monospace">// PORTAL_SISTEMAS_V1.0</span>
        </div>
    </nav>

    <div class="portal-viewport">
        <div class="container portal-container">

            <div class="portal-header-panel">
                <div>
                    <span class="portal-user-tag">Infraestructura lúdica // Núcleo Activo</span>
                    <h1 class="portal-welcome-title text-white">{{ $proyecto['nombre'] }}</h1>
                </div>
                <div>
                    <a href="{{ route('portal.dashboard') }}" class="btn-portal-back">
                        <i class="fas fa-arrow-left"></i>
                        <span>Volver a la Consola</span>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="project-glass-card">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <div>
                                <span class="portal-user-tag" style="color: #9ca3af !important;">Despliegue y Arquitectura</span>
                                <h2 class="text-white fw-bold mt-1" style="font-size: 1.4rem;">Módulos del Sistema</h2>
                            </div>
                            <span class="status-badge-active"><i class="fas fa-microchip me-2"></i>{{ $proyecto['estado'] }}</span>
                        </div>

                        <p style="color: #9ca3af; font-size: 0.95rem; line-height: 1.6;" class="mb-4">
                            A continuación se detallan los componentes lógicos mapeados y el estado actual de su desarrollo en el clúster.
                        </p>

                        <div class="d-flex flex-column gap-3">
                            @foreach($proyecto['hitos'] as $hito)
                            <div class="modulo-item d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h4 class="text-white fw-600 mb-1" style="font-size: 1.05rem;">{{ $hito['titulo'] }}</h4>
                                    <span class="small font-monospace" style="color: #9ca3af !important;">{{ $hito['descripcion'] }}</span>
                                </div>
                                <span class="small font-monospace" style="color: {{ $hito['color'] }};">{{ $hito['estado'] }}</span>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
