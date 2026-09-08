@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    .admin-viewport {
        background: #030712 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        min-height: calc(100vh - 75px);
        color: #ffffff;
        position: relative;
        padding: 120px 20px 60px 20px;
    }

    .admin-viewport::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 50% 30%, rgba(6, 182, 212, 0.08) 0%, transparent 60%),
            radial-gradient(circle at 80% 70%, rgba(138, 43, 226, 0.06) 0%, transparent 50%);
        z-index: 1;
        pointer-events: none;
    }

    .card-glass-neon {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(6, 182, 212, 0.15) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
        border-radius: 20px;
        padding: 35px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        z-index: 5;
    }

    .card-glass-neon:hover {
        border-color: rgba(6, 182, 212, 0.5) !important;
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.2) !important;
    }

    .form-glass {
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 12px !important;
        color: #ffffff !important;
        padding: 14px 18px !important;
        transition: all 0.3s ease !important;
    }

    .form-glass:focus {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2) !important;
        background: rgba(255, 255, 255, 0.05) !important;
    }

    .table-custom {
        color: white !important;
    }

    .table-custom thead,
    .table-custom tr,
    .table-custom th {
        background: transparent !important;
        background-color: transparent !important;
        border-color: rgba(255, 255, 255, 0.06) !important;
    }

    .table-custom th {
        font-family: monospace;
        color: #06b6d4 !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 2px;
        padding: 16px 12px !important;
    }

    .table-custom td {
        background: transparent !important;
        background-color: transparent !important;
        padding: 16px 12px !important;
        border-color: rgba(255, 255, 255, 0.04) !important;
    }

    .badge-active {
        background: rgba(34, 197, 94, 0.1);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
    }

    .badge-pending {
        background: rgba(234, 179, 8, 0.1);
        color: #facc15;
        border: 1px solid rgba(234, 179, 8, 0.3);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
    }
</style>

<div class="admin-viewport">
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12" style="position: relative; z-index: 5;">
                <h2 class="fw-bold text-white" style="font-size: 2.2rem; letter-spacing: -1px; margin-bottom: 6px;">
                    Control de Accesos</h2>
                <span style="font-family: monospace; font-size: 0.75rem; color: rgba(255,255,255,0.4); letter-spacing: 2px; text-transform: uppercase;">
                    Panel de Alta de Clientes</span>
            </div>
        </div>

        @if(session('success'))
            @php
                $actLink = session('activation_link') ?? (str_contains(session('success'), 'http') ? explode('Link de activación: ', session('success'))[1] ?? '' : '');
                $clienteNombre = session('cliente_nombre') ?? 'estimado cliente';
                $mensajeWhatsApp = urlencode("¡Hola {$clienteNombre}! Te damos la bienvenida a Software Tech. Aquí tienes tu enlace oficial para activar tu portal y dar seguimiento a tu proyecto en tiempo real:\n\n{$actLink}");
            @endphp
            <div class="row mb-4" style="position: relative; z-index: 5;">
                <div class="col-md-12">
                    <div class="alert alert-success border-0 px-4 py-3" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.4) !important; border-radius: 14px; color: #34d399;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <h6 class="mb-1 fw-bold text-white"><i class="fas fa-check-circle text-success me-2"></i>{{ session('success') }}</h6>
                                @if($actLink)
                                    <div class="font-mono text-truncate" style="font-size: 0.78rem; color: rgba(255,255,255,0.7); max-width: 600px;">
                                        {{ $actLink }}
                                    </div>
                                @endif
                            </div>
                            @if($actLink)
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-light font-mono px-3" style="font-size: 0.75rem; border-radius: 8px;" onclick="copiarAlPortapapeles('{{ $actLink }}', this)">
                                        <i class="fas fa-copy me-1"></i> Copiar Link
                                    </button>
                                    <a href="https://wa.me/?text={{ $mensajeWhatsApp }}" target="_blank" class="btn btn-sm font-mono px-3 text-white" style="font-size: 0.75rem; border-radius: 8px; background: #25D366; border: 1px solid #20ba5a;">
                                        <i class="fab fa-whatsapp me-1"></i> Enviar por WhatsApp
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card-glass-neon">
                    <h5 class="fw-bold text-white mb-4" style="letter-spacing: -0.5px;">Registrar Terminal</h5>

                    <form action="{{ route('admin.clientes.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-bold" style="letter-spacing: 0.5px;">
                                Nombre del Cliente
                            </label>
                            <input type="text" name="name" class="form-control form-glass" required autocomplete="off" placeholder="Ej. Juan Pérez">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white-50 small fw-bold" style="letter-spacing: 0.5px;">
                                Correo de Destino
                            </label>
                            <input type="email" name="email" class="form-control form-glass" required autocomplete="off" placeholder="cliente@empresa.com">
                        </div>

                        <button type="submit" class="btn btn-info w-100 py-2.5 fw-bold text-white"
                            style="background: #06b6d4; border: none; border-radius: 12px; box-shadow: 0 0 15px rgba(6, 182, 212, 0.3);">
                            Generar Invitación
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card-glass-neon">
                    <h5 class="fw-bold text-white mb-4" style="letter-spacing: -0.5px;">Sistemas Vinculados</h5>
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Correo Electrónico</th>
                                    <th>Estado</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clientes as $cliente)
                                    @php
                                        $linkCliente = $cliente->activation_token ? route('portal.activate.form', $cliente->activation_token) : null;
                                        $waText = $linkCliente ? urlencode("¡Hola {$cliente->name}! Te damos la bienvenida a Software Tech. Aquí tienes tu enlace oficial para activar tu portal de cliente:\n\n{$linkCliente}") : '';
                                    @endphp
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.02);">
                                        <td style="color: #ffffff !important; padding: 16px 0; font-size: 0.9rem; font-weight: 600;">
                                            {{ $cliente->name }}
                                        </td>
                                        <td style="color: rgba(255, 255, 255, 0.5) !important; padding: 16px 0; font-family: monospace; font-size: 0.85rem;">
                                            {{ $cliente->email }}
                                        </td>
                                        <td style="padding: 16px 0;">
                                            @if($cliente->active == 1)
                                                <span class="badge" style="background: rgba(74, 222, 128, 0.05); border: 1px solid rgba(74, 222, 128, 0.3); color: #4ade80; padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                                    <span style="display: inline-block; width: 6px; height: 6px; background: #4ade80; border-radius: 50%; margin-right: 6px; box-shadow: 0 0 8px #4ade80;"></span>Activado
                                                </span>
                                            @elseif($cliente->active == 0)
                                                <span class="badge" style="background: rgba(250, 204, 21, 0.05); border: 1px solid rgba(250, 204, 21, 0.2); color: #facc15; padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                                    Pendiente
                                                </span>
                                            @elseif($cliente->active == 2)
                                                <span class="badge" style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                                    Desactivado
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 16px 0; text-align: right;">
                                            @if($cliente->active == 0 && $linkCliente)
                                                <div class="d-inline-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-info font-mono" style="font-size: 0.7rem; border-radius: 6px; padding: 4px 10px;" onclick="copiarAlPortapapeles('{{ $linkCliente }}', this)" title="Copiar link de activación">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                    <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="btn btn-sm font-mono text-white" style="font-size: 0.7rem; border-radius: 6px; background: #25D366; padding: 4px 10px;" title="Enviar por WhatsApp">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span style="font-size: 0.75rem; color: rgba(255,255,255,0.3); font-family: monospace;">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copiarAlPortapapeles(texto, btn) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(texto).then(() => {
            mostrarCopiado(btn);
        });
    } else {
        const tempInput = document.createElement('textarea');
        tempInput.value = texto;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        mostrarCopiado(btn);
    }
}

function mostrarCopiado(btn) {
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check text-success"></i> ¡Copiado!';
    setTimeout(() => {
        btn.innerHTML = originalHtml;
    }, 2000);
}
</script>
@endsection
