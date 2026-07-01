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
        padding: 80px 20px;
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
                    Módulo Nexus: Alta de Clientes</span>
            </div>
        </div>

        @if(session('success'))
            <div class="row mb-4" style="position: relative; z-index: 5;">
                <div class="col-md-12">
                    <div class="alert alert-success border-0 px-4 py-3" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2) !important; border-radius: 12px; color: #4ade80;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <span class="small font-mono">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            </span>
                            <button class="btn btn-sm btn-outline-success font-mono px-3" style="font-size: 0.7rem; border-radius: 8px;" onclick="navigator.clipboard.writeText('{{ str_replace('Cliente registrado. Link de activación: ', '', session('success')) }}'); alert('¡Link copiado al portapapeles!');">
                                <i class="fas fa-copy me-1"></i> Copiar Link
                            </button>
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
                            <input type="text" name="name" class="form-control form-glass" required autocomplete="off">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white-50 small fw-bold" style="letter-spacing: 0.5px;">
                                Correo de Destino
                            </label>
                            <input type="email" name="email" class="form-control form-glass" required autocomplete="off">
                        </div>

                        <button type="submit" class="btn btn-info w-100 py-2.5 fw-bold text-white"
                            style="background: #06b6d4; border: none; border-radius: 12px; box-shadow: 0 0 15px rgba(6, 182, 212, 0.3);">
                            Enviar Invitación
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clientes as $cliente)
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.02);">
                                        <td style="color: #ffffff !important; padding: 16px 0; font-size: 0.9rem; font-weight: 600;">
                                            {{ $cliente->name }}
                                        </td>
                                        <td style="color: rgba(255, 255, 255, 0.5) !important; padding: 16px 0; font-family: monospace; font-size: 0.85rem;">
                                            {{ $cliente->email }}
                                        </td>
                                        <td style="padding: 16px 0; text-align: right;">
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
@endsection
