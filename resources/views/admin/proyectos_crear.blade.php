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
        padding: 60px 20px;
    }

    .admin-viewport::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 50% 30%, rgba(6, 182, 212, 0.05) 0%, transparent 60%);
        z-index: 1;
        pointer-events: none;
    }

    .admin-container {
        position: relative;
        z-index: 5;
    }

    .card-glass-minimal {
        background: rgba(255, 255, 255, 0.01) !important;
        border: 1px solid rgba(255, 255, 255, 0.04) !important;
        backdrop-filter: blur(16px) saturate(120%) !important;
        -webkit-backdrop-filter: blur(16px) saturate(120%) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4) !important;
        border-radius: 20px;
        padding: 40px;
        position: relative;
        overflow: visible; /* Permitir que los menús floten hacia afuera */
    }

    .card-glass-minimal::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 2px;
        background: #06b6d4;
        opacity: 0.8;
    }

    .form-label-tech {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: rgba(255, 255, 255, 0.4) !important;
        display: block;
        margin-bottom: 8px;
    }

    .form-control-tech {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: rgba(255, 255, 255, 0.85) !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-control-tech:focus {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2) !important;
        background: rgba(255, 255, 255, 0.03) !important;
        outline: none;
    }

    /* DROPDOWNS EXCLUSIVOS CON TU DISEÑO KANBAN */
    .custom-dropdown {
        position: relative;
        width: 100%;
    }

    .dropdown-trigger {
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dropdown-trigger i {
        color: rgba(255, 255, 255, 0.4);
        transition: transform 0.3s ease;
        font-size: 0.8rem;
    }

    .custom-dropdown.open .dropdown-trigger {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2) !important;
    }

    .custom-dropdown.open .dropdown-trigger i {
        transform: rotate(180deg);
    }

    .dropdown-menu-glass {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        width: 100%;
        background: rgba(11, 15, 25, 0.98) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border-radius: 10px;
        padding: 6px 0;
        margin: 0;
        list-style: none;
        z-index: 999;
        box-shadow: 0 10px 30px rgba(0,0,0,0.6);
        max-height: 240px;
        overflow-y: auto;
    }

    .custom-dropdown.open .dropdown-menu-glass {
        display: block;
    }

    .dropdown-item-tech {
        padding: 12px 16px;
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    /* El resaltado blanco casi transparente que pediste */
    .dropdown-item-tech:hover {
        background: rgba(255, 255, 255, 0.06) !important;
        color: #ffffff !important;
    }

    .action-btn {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.6);
        padding: 8px 24px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .action-btn:hover, .action-btn.active {
        color: #fff;
        background: rgba(6, 182, 212, 0.15);
        border-color: #06b6d4;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        opacity: 0.4;
        cursor: pointer;
    }
</style>

<div class="admin-viewport">
    <div class="container admin-container">

        <div class="row mb-5 text-center">
            <div class="col-md-8 mx-auto">
                <h2 class="fw-bold text-white mb-2" style="font-size: 2.3rem; letter-spacing: -1px; font-weight: 800 !important;">
                    ¿Deseas iniciar un nuevo proyecto?
                </h2>
                <p class="text-muted small mx-auto" style="max-width: 580px; line-height: 1.6; color: rgba(255, 255, 255, 0.4) !important;">
                    El nuevo proyecto se registrará en tu gestor de proyectos <span style="color: #06b6d4; font-weight: 600;">Kanban Management</span> y aparecerá en tiempo real a los clientes y empleados asignados.
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card-glass-minimal">

                    <form action="{{ route('admin.proyectos.store') }}" method="POST" id="projectForm">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label-tech">Nombre del Proyecto</label>
                            <input type="text" name="nombre" class="form-control-tech w-100" required autocomplete="off">
                        </div>

                        <div class="mb-4">
                            <label class="form-label-tech">Tipo de Servicio</label>
                            <div class="custom-dropdown" id="dropdown-servicio">
                                <div class="form-control-tech dropdown-trigger w-100">
                                    <span class="selected-text">Seleccionar Servicio</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <input type="hidden" name="servicio" required>
                                <ul class="dropdown-menu-glass">
                                    <li class="dropdown-item-tech" data-value="IA">IA (Inteligencia Artificial)</li>
                                    <li class="dropdown-item-tech" data-value="Desarrollo Web">Desarrollo Web</li>
                                    <li class="dropdown-item-tech" data-value="Desarrollo Móvil">Desarrollo Móvil</li>
                                    <li class="dropdown-item-tech" data-value="Ciberseguridad">Ciberseguridad</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-tech">Cliente Asociado</label>
                            <div class="custom-dropdown" id="dropdown-cliente">
                                <div class="form-control-tech dropdown-trigger w-100">
                                    <span class="selected-text">Seleccionar Cliente</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <input type="hidden" name="user_id" required>
                                <ul class="dropdown-menu-glass">
                                    @foreach($clientes as $cliente)
                                        <li class="dropdown-item-tech" data-value="{{ $cliente->id }}">{{ $cliente->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-tech">Encargado / Developer</label>
                            <div class="custom-dropdown" id="dropdown-developer">
                                <div class="form-control-tech dropdown-trigger w-100">
                                    <span class="selected-text">Seleccionar Encargado</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <input type="hidden" name="developer_id" required>
                                <ul class="dropdown-menu-glass">
                                    @foreach($desarrolladores as $dev)
                                        <li class="dropdown-item-tech" data-value="{{ $dev->id }}">{{ $dev->name }} ({{ $dev->role }})</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-tech">Prioridad de Ejecución</label>
                            <div class="custom-dropdown" id="dropdown-priority">
                                <div class="form-control-tech dropdown-trigger w-100">
                                    <span class="selected-text">Medio</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <input type="hidden" name="priority" value="medio" required>
                                <ul class="dropdown-menu-glass">
                                    <li class="dropdown-item-tech" data-value="bajo">Bajo</li>
                                    <li class="dropdown-item-tech" data-value="medio">Medio</li>
                                    <li class="dropdown-item-tech" data-value="alto">Alto</li>
                                    <li class="dropdown-item-tech" data-value="critico">Crítico</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label-tech">Fecha Límite de Sprint</label>
                            <input type="date" name="siguiente_entrega" class="form-control-tech w-100">
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
                            <a href="{{ route('admin.proyectos.index') }}" class="action-btn text-center" style="line-height: 20px;">Cancelar</a>
                            <button type="submit" class="action-btn active" style="background: #06b6d4; border-color: #06b6d4; color: #fff;">Registrar Proyecto</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.custom-dropdown');

    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const menu = dropdown.querySelector('.dropdown-menu-glass');
        const items = dropdown.querySelectorAll('.dropdown-item-tech');
        const hiddenInput = dropdown.querySelector('input[type="hidden"]');
        const selectedText = dropdown.querySelector('.selected-text');

        // Abrir / Cerrar menú al hacer clic
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();

            // Cerrar los otros dropdwons abiertos
            dropdowns.forEach(other => {
                if (other !== dropdown) other.classList.remove('open');
            });

            dropdown.classList.toggle('open');
        });

        // Seleccionar un elemento de la lista
        items.forEach(item => {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = this.getAttribute('data-value');
                const text = this.innerText;

                hiddenInput.value = value;
                selectedText.innerText = text;
                dropdown.classList.remove('open');
            });
        });
    });

    // Cerrar los menús si se hace clic fuera de ellos
    document.addEventListener('click', function() {
        dropdowns.forEach(dropdown => dropdown.classList.remove('open'));
    });
});
</script>
@endsection
