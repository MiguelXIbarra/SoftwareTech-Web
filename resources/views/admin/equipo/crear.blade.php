@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap');

    .admin-viewport {
        background: #030712 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        min-height: calc(100vh - 75px);
        color: #ffffff;
        position: relative;
        padding: 120px 20px 60px 20px;
    }

    .form-box-neon {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(6, 182, 212, 0.25) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6) !important;
        max-width: 850px;
        margin: 0 auto;
    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    .form-control-tech {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #fff !important;
        font-family: monospace;
        border-radius: 8px;
        padding: 10px 12px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .form-control-tech:focus {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.3) !important;
        outline: none;
    }

    /* DROPDOWN CUSTOM CIAN */
    .custom-dropdown {
        position: relative;
        width: 100%;
    }

    .dropdown-trigger {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(6, 182, 212, 0.35) !important;
        color: #fff !important;
        font-family: monospace;
        border-radius: 8px;
        padding: 10px 40px 10px 12px;
        width: 100%;
        text-align: left;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
        user-select: none;
        box-shadow: 0 0 12px rgba(6, 182, 212, 0.1);
    }

    .dropdown-trigger:focus, .custom-dropdown.open .dropdown-trigger {
        border-color: #00d4ff !important;
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.35) !important;
        outline: none;
    }

    .dropdown-trigger::after {
        content: '\f107';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #00d4ff;
        transition: transform 0.3s ease;
    }

    .custom-dropdown.open .dropdown-trigger::after {
        transform: translateY(-50%) rotate(180deg);
    }

    .dropdown-menu-custom {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        width: 100%;
        background: rgba(5, 11, 20, 0.98) !important;
        border: 1px solid rgba(6, 182, 212, 0.3) !important;
        backdrop-filter: blur(20px);
        border-radius: 8px;
        margin: 0;
        padding: 6px 0;
        list-style: none;
        z-index: 999;
        max-height: 200px;
        overflow-y: auto;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.25s ease;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.8), 0 0 15px rgba(6, 182, 212, 0.15);
    }

    .custom-dropdown.open .dropdown-menu-custom {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-menu-custom li {
        padding: 10px 14px;
        color: rgba(255, 255, 255, 0.8);
        font-family: monospace;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .dropdown-menu-custom li:hover {
        background: rgba(6, 182, 212, 0.22) !important;
        color: #00d4ff !important;
        font-weight: 600;
    }

    .dropdown-menu-custom li.selected {
        background: rgba(6, 182, 212, 0.3) !important;
        color: #00d4ff !important;
        font-weight: bold;
    }

    .dropdown-menu-custom::-webkit-scrollbar {
        width: 6px;
    }
    .dropdown-menu-custom::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.2);
    }
    .dropdown-menu-custom::-webkit-scrollbar-thumb {
        background: #06b6d4;
        border-radius: 4px;
    }

    /* FILAS DINÁMICAS Y BOTÓN BORRAR */
    .project-row-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        margin-bottom: 16px;
        width: 100%;
    }

    .project-row-input {
        background: rgba(255, 255, 255, 0.01);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 20px;
        width: 100%;
        flex: none;
        transition: width 0.35s ease;
    }

    .btn-row-delete {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 42px;
        background: rgba(239, 68, 68, 0.02);
        border: 1px solid rgba(239, 68, 68, 0.4);
        border-radius: 8px;
        color: #f87171;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transform: translateX(20px);
        cursor: pointer;
        transition: all 0.35s ease;
    }

    .btn-row-delete:hover {
        background: rgba(239, 68, 68, 0.15);
        border-color: #ef4444;
        color: #ffffff;
    }

    .project-row-wrapper:hover .project-row-input {
        width: calc(100% - 54px);
    }

    .project-row-wrapper:hover .btn-row-delete {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    .action-hud-btn {
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.4);
        color: #22d3ee;
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .action-hud-btn:hover {
        color: #fff;
        background: #06b6d4;
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.4);
    }

    .btn-logout {
        background: transparent;
        color: rgba(255, 255, 255, 0.5);
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-logout:hover {
        color: #fff;
    }

    .filter-btn {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.7);
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-btn:hover {
        background: rgba(6, 182, 212, 0.1);
        border-color: #06b6d4;
        color: #00d4ff;
    }

    .custom-leader-switch:checked {
        background-color: #facc15 !important;
        border-color: #facc15 !important;
        box-shadow: 0 0 10px rgba(250, 204, 21, 0.5) !important;
    }
</style>

<div class="admin-viewport">
    <div class="container">

        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold text-white mb-1" style="font-size: 2rem; letter-spacing: -0.5px;">Integrar Miembro Operativo</h2>
                <span class="font-mono text-info" style="font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;">Nueva Entrada en Terminal Corporativa</span>
            </div>
        </div>

        <div class="form-box-neon">
            <form action="{{ route('admin.equipo.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Nombre Completo</label>
                        <input type="text" name="name" class="form-control form-control-tech" required autocomplete="off">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Email Terminal</label>
                        <input type="email" name="email" class="form-control form-control-tech" required autocomplete="off">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Contraseña de Acceso</label>
                        <input type="password" name="password" class="form-control form-control-tech" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Rol Corporativo</label>

                        <div class="custom-dropdown">
                            <input type="hidden" name="role" id="input_role" value="empleado">
                            <div class="dropdown-trigger" onclick="toggleDropdown(this)">
                                Empleado
                            </div>
                            <ul class="dropdown-menu-custom">
                                <li data-value="superadmin" onclick="selectDropdownOption(this, 'input_role')">Superadministrador</li>
                                <li data-value="admin" onclick="selectDropdownOption(this, 'input_role')">Administrador</li>
                                <li data-value="empleado" class="selected" onclick="selectDropdownOption(this, 'input_role')">Empleado</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4" style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 24px;">
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Edad Cronológica</label>
                        <input type="number" name="edad" class="form-control form-control-tech" min="18">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Horas de Trabajo Semanales</label>
                        <input type="number" name="capacity" class="form-control form-control-tech" min="0" max="40" value="40" required>
                    </div>
                </div>

                <h4 class="fw-bold text-white mt-5 mb-3" style="font-size: 0.85rem; font-family: monospace; letter-spacing: 1.5px; text-transform: uppercase; color: #22d3ee;">
                    Asignación de Células y Parámetros Operativos
                </h4>

                <!-- CONTENEDOR INICIALMENTE VACÍO SIN PROYECTOS PREESTABLECIDOS -->
                <div id="wrapperProyectosAsignados"></div>

                <div class="text-start mb-4">
                    <button type="button" onclick="addProjectSlot()" class="filter-btn">+ Añadir Ranura de Proyecto</button>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5 pt-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <a href="{{ route('admin.equipo') }}" class="btn-logout">Cancelar</a>
                    <button type="submit" class="action-hud-btn">Crear Nueva Identidad</button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    let projectIndex = 0;

    function toggleDropdown(triggerElement) {
        const parent = triggerElement.parentElement;
        document.querySelectorAll('.custom-dropdown').forEach(dd => {
            if (dd !== parent) dd.classList.remove('open');
        });
        parent.classList.toggle('open');
    }

    function selectDropdownOption(liElement, hiddenInputId) {
        const value = liElement.getAttribute('data-value');
        const label = liElement.innerText;
        const parent = liElement.closest('.custom-dropdown');

        const hiddenInput = document.getElementById(hiddenInputId);
        if (hiddenInput) hiddenInput.value = value;

        parent.querySelector('.dropdown-trigger').innerText = label;

        parent.querySelectorAll('li').forEach(item => item.classList.remove('selected'));
        liElement.classList.add('selected');

        parent.classList.remove('open');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.custom-dropdown').forEach(dd => dd.classList.remove('open'));
        }
    });

    function addProjectSlot() {
        const activeSlots = document.querySelectorAll('#wrapperProyectosAsignados .project-row-wrapper').length;
        if (activeSlots >= 3) {
            alert('[LIMITATION]: Máximo 3 proyectos por miembro de forma simultánea.');
            return;
        }

        const wrapper = document.getElementById('wrapperProyectosAsignados');
        const div = document.createElement('div');
        div.className = 'project-row-wrapper';
        div.id = `project_slot_${projectIndex}`;
        div.innerHTML = `
            <div class="project-row-input row align-items-center g-2">
                <div class="col-md-3">
                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Proyecto</label>

                    <div class="custom-dropdown">
                        <input type="hidden" name="proyectos[${projectIndex}][id]" id="proy_id_${projectIndex}" value="">
                        <div class="dropdown-trigger" onclick="toggleDropdown(this)">
                            SELECCIONAR ESCENARIO
                        </div>
                        <ul class="dropdown-menu-custom">
                            <li data-value="" onclick="selectDropdownOption(this, 'proy_id_${projectIndex}')">SELECCIONAR ESCENARIO</li>
                            @foreach($proyectos as $p)
                                <li data-value="{{ $p->id }}" onclick="selectDropdownOption(this, 'proy_id_${projectIndex}')">{{ $p->nombre }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Sueldo Asignado ($)</label>
                    <input type="number" name="proyectos[${projectIndex}][sueldo]" class="form-control form-control-tech" min="0" value="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Importancia / Rol Crítico</label>

                    <div class="custom-dropdown">
                        <input type="hidden" name="proyectos[${projectIndex}][importancia]" id="proy_imp_${projectIndex}" value="Media">
                        <div class="dropdown-trigger" onclick="toggleDropdown(this)">
                            Media
                        </div>
                        <ul class="dropdown-menu-custom">
                            <li data-value="Baja" onclick="selectDropdownOption(this, 'proy_imp_${projectIndex}')">Baja</li>
                            <li data-value="Media" class="selected" onclick="selectDropdownOption(this, 'proy_imp_${projectIndex}')">Media</li>
                            <li data-value="Alta" onclick="selectDropdownOption(this, 'proy_imp_${projectIndex}')">Alta</li>
                            <li data-value="Crítica" onclick="selectDropdownOption(this, 'proy_imp_${projectIndex}')">Crítica</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label font-mono text-white-50 d-block" style="font-size: 0.65rem;">¿Líder del Proyecto?</label>
                    <div class="form-check form-switch d-flex align-items-center gap-2 pt-1">
                        <input class="form-check-input custom-leader-switch" type="checkbox" name="proyectos[${projectIndex}][es_lider]" id="proy_leader_${projectIndex}" value="1" style="cursor: pointer; width: 2.2em; height: 1.2em; background-color: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.25);">
                        <label class="form-check-label font-mono text-warning" for="proy_leader_${projectIndex}" style="font-size: 0.75rem; cursor: pointer;">
                            <i class="fas fa-crown"></i> Líder
                        </label>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-row-delete" onclick="removeProjectSlot(${projectIndex})">
                <i class="fas fa-trash-alt"></i>
            </button>
        `;
        wrapper.appendChild(div);
        projectIndex++;
    }

    function removeProjectSlot(index) {
        const slot = document.getElementById(`project_slot_${index}`);
        if (slot) {
            slot.style.opacity = '0';
            slot.style.transform = 'scale(0.95)';
            setTimeout(() => {
                slot.remove();
            }, 250);
        }
    }
</script>
@endsection
