@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

    .admin-viewport {
        background: #030712 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        min-height: calc(100vh - 75px);
        color: #ffffff;
        position: relative;
        padding: 120px 20px 80px 20px;
    }

    .admin-viewport::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 50% 20%, rgba(6, 182, 212, 0.08) 0%, transparent 60%),
                    radial-gradient(circle at 85% 70%, rgba(138, 43, 226, 0.06) 0%, transparent 50%);
        z-index: 1;
        pointer-events: none;
    }

    .card-glass-neon {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.65) 0%, rgba(10, 15, 30, 0.8) 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
        border-radius: 22px;
        padding: 35px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        z-index: 5;
    }

    .card-glass-neon:hover {
        border-color: rgba(6, 182, 212, 0.35) !important;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.6), 0 0 25px rgba(6, 182, 212, 0.1) !important;
    }

    .form-glass {
        background: rgba(15, 23, 42, 0.7) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px !important;
        color: #ffffff !important;
        padding: 14px 18px !important;
        transition: all 0.3s ease !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    .form-glass::placeholder {
        color: rgba(148, 163, 184, 0.6) !important;
    }

    .form-glass:focus {
        background: rgba(15, 23, 42, 0.95) !important;
        border-color: #06b6d4 !important;
        box-shadow: 0 0 18px rgba(6, 182, 212, 0.3) !important;
        outline: none !important;
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
        font-family: 'JetBrains Mono', monospace;
        color: #38bdf8 !important;
        text-transform: uppercase;
        font-size: 0.72rem;
        letter-spacing: 2px;
        padding: 18px 14px !important;
        font-weight: 700;
    }

    .table-custom td {
        background: transparent !important;
        background-color: transparent !important;
        padding: 16px 14px !important;
        border-color: rgba(255, 255, 255, 0.04) !important;
        vertical-align: middle;
    }

    .client-avatar-badge {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(138, 43, 226, 0.2));
        border: 1px solid rgba(6, 182, 212, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
        font-weight: 700;
        color: #38bdf8;
        flex-shrink: 0;
    }

    .btn-submit-action {
        background: linear-gradient(135deg, #0284c7 0%, #06b6d4 50%, #7c3aed 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff !important;
        font-weight: 700;
        letter-spacing: 0.5px;
        border-radius: 12px;
        padding: 14px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.3);
    }

    .btn-submit-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.5);
    }
</style>

<div class="admin-viewport">
    <div class="container mt-4" style="max-width: 1200px;">
        <div class="row mb-4">
            <div class="col-md-12" style="position: relative; z-index: 5;">
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; color: #22d3ee; letter-spacing: 2px; text-transform: uppercase; font-weight: 700;">
                    <i class="fas fa-users-cog me-1"></i> CONTROL DE ACCESOS // V4.5
                </span>
                <h2 class="fw-bold text-white mt-1 mb-0" style="font-size: 2.2rem; letter-spacing: -0.8px; font-weight: 800;">
                    Gestión de Clientes
                </h2>
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
                    <div class="alert alert-success border-0 px-4 py-3" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.4) !important; border-radius: 16px; color: #34d399;">
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
                    <h5 class="fw-bold text-white mb-4" style="letter-spacing: -0.5px; font-weight: 800;">
                        <i class="fas fa-user-plus text-cyan me-2"></i> Registrar Cliente
                    </h5>

                    <form action="{{ route('admin.clientes.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-bold" style="letter-spacing: 0.5px; font-family: 'JetBrains Mono', monospace; font-size: 0.72rem;">
                                NOMBRE DEL CLIENTE
                            </label>
                            <input type="text" name="name" class="form-control form-glass" required autocomplete="off" placeholder="Ej. Carlos Mendoza">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white-50 small fw-bold" style="letter-spacing: 0.5px; font-family: 'JetBrains Mono', monospace; font-size: 0.72rem;">
                                CORREO DE DESTINO
                            </label>
                            <input type="email" name="email" class="form-control form-glass" required autocomplete="off" placeholder="cliente@empresa.com">
                        </div>

                        <button type="submit" class="btn btn-submit-action w-100">
                            <i class="fas fa-paper-plane me-2"></i> Generar Invitación
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card-glass-neon">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-white mb-0" style="letter-spacing: -0.5px; font-weight: 800;">
                            <i class="fas fa-network-wired text-purple-400 me-2"></i> Cuentas Vinculadas
                        </h5>
                        <span class="font-mono" style="font-size: 0.72rem; color: #64748b;">
                            Total: {{ $clientes->count() }}
                        </span>
                    </div>

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
                                        $iniciales = strtoupper(substr($cliente->name, 0, 2));
                                    @endphp
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="client-avatar-badge">{{ $iniciales }}</div>
                                                <div style="color: #ffffff !important; font-size: 0.92rem; font-weight: 700;">
                                                    {{ $cliente->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td style="color: #94a3b8 !important; font-family: 'JetBrains Mono', monospace; font-size: 0.82rem;">
                                            {{ $cliente->email }}
                                        </td>
                                        <td>
                                            @if($cliente->active == 1)
                                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.35); color: #34d399; padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; font-family: 'JetBrains Mono', monospace;">
                                                    <span style="display: inline-block; width: 6px; height: 6px; background: #34d399; border-radius: 50%; margin-right: 6px; box-shadow: 0 0 8px #34d399;"></span>Activado
                                                </span>
                                            @elseif($cliente->active == 0)
                                                <span class="badge" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.35); color: #fbbf24; padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; font-family: 'JetBrains Mono', monospace;">
                                                    Pendiente
                                                </span>
                                            @elseif($cliente->active == 2)
                                                <span class="badge" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.35); color: #f87171; padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; font-family: 'JetBrains Mono', monospace;">
                                                    Desactivado
                                                </span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            @if($cliente->active == 0 && $linkCliente)
                                                <div class="d-inline-flex gap-2">
                                                    <button type="button" class="btn btn-sm font-mono" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3); color: #22d3ee; font-size: 0.72rem; border-radius: 8px; padding: 6px 12px;" onclick="copiarAlPortapapeles('{{ $linkCliente }}', this)" title="Copiar link de activación">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                    <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="btn btn-sm font-mono text-white" style="font-size: 0.72rem; border-radius: 8px; background: #25D366; border: 1px solid #20ba5a; padding: 6px 12px;" title="Enviar por WhatsApp">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span style="font-size: 0.75rem; color: #64748b; font-family: 'JetBrains Mono', monospace;">-</span>
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
