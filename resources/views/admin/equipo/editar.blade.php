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
        padding: 40px 20px;
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

    /* Ocultar flechas nativas de inputs numéricos */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    /* ESTILOS DEL DROPDOWN CUSTOM CYBERPUNK (SOFTWARE TECH) */
    .custom-dropdown {
        position: relative;
        width: 100%;
    }

    .dropdown-trigger {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
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
    }

    .dropdown-trigger:focus, .custom-dropdown.open .dropdown-trigger {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.3) !important;
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
        color: #06b6d4;
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
        background: rgba(3, 7, 18, 0.95) !important;
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
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.7), 0 0 20px rgba(6, 182, 212, 0.1);
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

    /* Colores corporativos en el hover: Fondo cyan degradado y texto neón */
    .dropdown-menu-custom li:hover {
        background: rgba(6, 182, 212, 0.15) !important;
        color: #00d4ff !important;
        padding-left: 18px;
    }

    .dropdown-menu-custom li.selected {
        background: rgba(6, 182, 212, 0.25) !important;
        color: #00d4ff !important;
        font-weight: bold;
    }

    /* Scrollbar estilizada para el menú */
    .dropdown-menu-custom::-webkit-scrollbar {
        width: 6px;
    }
    .dropdown-menu-custom::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.1);
    }
    .dropdown-menu-custom::-webkit-scrollbar-thumb {
        background: #06b6d4;
        border-radius: 4px;
    }

    /* RESTRICCIONES DE LA FILA DE PROYECTOS */
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
        transition: width 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease, background 0.3s ease;
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
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-row-delete:hover {
        background: rgba(239, 68, 68, 0.15);
        border-color: #ef4444;
        color: #ffffff;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.3);
    }

    .project-row-wrapper:hover .project-row-input {
        width: calc(100% - 54px);
        border-color: rgba(239, 68, 68, 0.2);
        background: rgba(239, 68, 68, 0.01);
    }

    .project-row-wrapper:hover .btn-row-delete {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    .action-hud-btn {
        background: rgba(6, 182, 212, 0.05);
        border: 1px solid rgba(6, 182, 212, 0.3);
        color: #22d3ee;
        padding: 8px 18px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .action-hud-btn:hover {
        color: #fff;
        background: #06b6d4;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
        transform: translateY(-1px);
    }

    .btn-logout {
        background: transparent;
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #ef4444;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .filter-btn {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.6);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        text-decoration: none;
    }
</style>

<div class="admin-viewport">
    <div class="container">

        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold text-white mb-1" style="font-size: 2rem; letter-spacing: -0.5px;">Modificar Nodo Operativo</h2>
                <span class="font-mono text-info" style="font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;">Modificación de Datos: {{ $miembro->name }}</span>
            </div>
        </div>

        <div class="form-box-neon">
            <form action="{{ route('admin.equipo.update', $miembro->id) }}" method="POST">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Nombre Completo</label>
                        <input type="text" name="name" class="form-control" value="{{ $miembro->name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Email Terminal</label>
                        <input type="email" name="email" class="form-control" value="{{ $miembro->email }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Contraseña (Dejar en blanco para mantener)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Rol Corporativo</label>

                        <div class="custom-dropdown">
                            <input type="hidden" name="role" id="input_role" value="{{ $miembro->role }}">
                            <div class="dropdown-trigger" onclick="toggleDropdown(this)">
                                {{ ucfirst($miembro->role) }}
                            </div>
                            <ul class="dropdown-menu-custom">
                                <li data-value="superadmin" class="{{ $miembro->role === 'superadmin' ? 'selected' : '' }}" onclick="selectDropdownOption(this, 'input_role')">Superadminin</li>
                                <li data-value="admin" class="{{ $miembro->role === 'admin' ? 'selected' : '' }}" onclick="selectDropdownOption(this, 'input_role')">Administrador</li>
                                <li data-value="empleado" class="{{ $miembro->role === 'empleado' ? 'selected' : '' }}" onclick="selectDropdownOption(this, 'input_role')">Empleado</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4" style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 24px;">
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Edad Cronológica</label>
                        <input type="number" name="edad" class="form-control" value="{{ $miembro->corporation->edad ?? '' }}" min="18">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Horas de Trabajo Semanales</label>
                        <input type="number" name="capacity" class="form-control" min="0" max="40" value="{{ $miembro->corporation->capacity ?? 0 }}" placeholder="Ej. 40" required>
                    </div>
                </div>

                <h4 class="fw-bold text-white mt-5 mb-3" style="font-size: 0.85rem; font-family: monospace; letter-spacing: 1.5px; text-transform: uppercase; color: #22d3ee;">
                    Reconfiguración de Proyectos Asignados (Max 3)
                </h4>

                <div id="wrapperProyectosAsignados" style="max-width: 780px;">
                    @php $idx = 0; @endphp
                    @foreach($miembro->proyectos as $assignedProject)
                        <div class="project-row-wrapper" id="project_slot_{{ $idx }}">
                            <div class="project-row-input row align-items-center g-2">
                                <div class="col-md-4">
                                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Proyecto</label>

                                    <div class="custom-dropdown">
                                        <input type="hidden" name="proyectos[{{ $idx }}][id]" id="proy_id_{{ $idx }}" value="{{ $assignedProject->id }}">
                                        <div class="dropdown-trigger" onclick="toggleDropdown(this)">
                                            {{ $assignedProject->nombre }}
                                        </div>
                                        <ul class="dropdown-menu-custom">
                                            @foreach($proyectos as $p)
                                                <li data-value="{{ $p->id }}" class="{{ $assignedProject->id === $p->id ? 'selected' : '' }}" onclick="selectDropdownOption(this, 'proy_id_{{ $idx }}')">{{ $p->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Sueldo Asignado ($)</label>
                                    <input type="number" name="proyectos[{{ $idx }}][sueldo]" class="form-control" min="0" value="{{ $assignedProject->pivot->sueldo_proyecto ?? 0 }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Importancia / Rol Crítico</label>

                                    <div class="custom-dropdown">
                                        <input type="hidden" name="proyectos[{{ $idx }}][importancia]" id="proy_imp_{{ $idx }}" value="{{ $assignedProject->pivot->importancia ?? 'Media' }}">
                                        <div class="dropdown-trigger" onclick="toggleDropdown(this)">
                                            {{ $assignedProject->pivot->importancia ?? 'Media' }}
                                        </div>
                                        <ul class="dropdown-menu-custom">
                                            <li data-value="Baja" class="{{ ($assignedProject->pivot->importancia ?? '') === 'Baja' ? 'selected' : '' }}" onclick="selectDropdownOption(this, 'proy_imp_{{ $idx }}')">Baja</li>
                                            <li data-value="Media" class="{{ ($assignedProject->pivot->importancia ?? '') === 'Media' ? 'selected' : '' }}" onclick="selectDropdownOption(this, 'proy_imp_{{ $idx }}')">Media</li>
                                            <li data-value="Alta" class="{{ ($assignedProject->pivot->importancia ?? '') === 'Alta' ? 'selected' : '' }}" onclick="selectDropdownOption(this, 'proy_imp_{{ $idx }}')">Alta</li>
                                            <li data-value="Crítica" class="{{ ($assignedProject->pivot->importancia ?? '') === 'Crítica' ? 'selected' : '' }}" onclick="selectDropdownOption(this, 'proy_imp_{{ $idx }}')">Crítica</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-row-delete" onclick="removeProjectSlot({{ $idx }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                        @php $idx++; @endphp
                    @endforeach

                    @if($miembro->proyectos->count() == 0)
                        <div class="project-row-wrapper" id="project_slot_0">
                            <div class="project-row-input row align-items-center g-2">
                                <div class="col-md-4">
                                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Proyecto</label>

                                    <div class="custom-dropdown">
                                        <input type="hidden" name="proyectos[0][id]" id="proy_id_0" value="">
                                        <div class="dropdown-trigger" onclick="toggleDropdown(this)">
                                            SELECCIONAR ESCENARIO
                                        </div>
                                        <ul class="dropdown-menu-custom">
                                            @foreach($proyectos as $p)
                                                <li data-value="{{ $p->id }}" onclick="selectDropdownOption(this, 'proy_id_0')">{{ $p->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Sueldo Asignado ($)</label>
                                    <input type="number" name="proyectos[0][sueldo]" class="form-control" min="0" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Importancia / Rol Crítico</label>

                                    <div class="custom-dropdown">
                                        <input type="hidden" name="proyectos[0][importancia]" id="proy_imp_0" value="Media">
                                        <div class="dropdown-trigger" onclick="toggleDropdown(this)">
                                            Media
                                        </div>
                                        <ul class="dropdown-menu-custom">
                                            <li data-value="Baja" onclick="selectDropdownOption(this, 'proy_imp_0')">Baja</li>
                                            <li data-value="Media" class="selected" onclick="selectDropdownOption(this, 'proy_imp_0')">Media</li>
                                            <li data-value="Alta" onclick="selectDropdownOption(this, 'proy_imp_0')">Alta</li>
                                            <li data-value="Crítica" onclick="selectDropdownOption(this, 'proy_imp_0')">Crítica</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-row-delete" onclick="removeProjectSlot(0)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                        @php $idx = 1; @endphp
                    @endif
                </div>

                <div class="text-start mb-4">
                    <button type="button" onclick="addProjectSlot()" class="filter-btn" style="border-radius: 6px; padding: 6px 14px;">+ Añadir Ranura de Proyecto</button>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5 pt-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <a href="{{ route('admin.equipo') }}" class="btn-logout" style="text-decoration: none;">Cancelar</a>
                    <button type="submit" class="action-hud-btn" style="padding: 12px 30px;">Actualizar Cambios</button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    let projectIndex = {{ $idx }};

    // Funciones de control globales para los Custom Dropdowns HUD
    function toggleDropdown(triggerElement) {
        const parent = triggerElement.parentElement;

        // Cerrar otros dropdowns activos
        document.querySelectorAll('.custom-dropdown').forEach(dd => {
            if (dd !== parent) dd.classList.remove('open');
        });

        parent.classList.toggle('open');
    }

    function selectDropdownOption(liElement, hiddenInputId) {
        const value = liElement.getAttribute('data-value');
        const label = liElement.innerText;
        const parent = liElement.closest('.custom-dropdown');

        // Asignar al input oculto
        document.getElementById(hiddenInputId).value = value;

        // Cambiar la vista del trigger principal
        parent.querySelector('.dropdown-trigger').innerText = label;

        // Cambiar estados visuales en la lista
        parent.querySelectorAll('li').forEach(item => item.classList.remove('selected'));
        liElement.classList.add('selected');

        // Cerrar contenedor
        parent.classList.remove('open');
    }

    // Cerrar los menús si se da click afuera de la caja HUD
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.custom-dropdown').forEach(dd => dd.classList.remove('open'));
        }
    });

    function addProjectSlot() {
        const activeSlots = document.querySelectorAll('.project-row-wrapper').length;
        if(activeSlots >= 3) {
            alert('[LIMITATION]: Máximo 3 proyectos por miembro de forma simultánea.');
            return;
        }

        const wrapper = document.getElementById('wrapperProyectosAsignados');
        const div = document.createElement('div');
        div.className = 'project-row-wrapper';
        div.id = `project_slot_${projectIndex}`;
        div.innerHTML = `
            <div class="project-row-input row align-items-center g-2">
                <div class="col-md-4">
                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Proyecto</label>

                    <div class="custom-dropdown">
                        <input type="hidden" name="proyectos[${projectIndex}][id]" id="proy_id_${projectIndex}" value="" required>
                        <div class="dropdown-trigger" onclick="toggleDropdown(this)">
                            SELECCIONAR ESCENARIO
                        </div>
                        <ul class="dropdown-menu-custom">
                            @foreach($proyectos as $p)
                                <li data-value="{{ $p->id }}" onclick="selectDropdownOption(this, 'proy_id_${projectIndex}')">{{ $p->nombre }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Sueldo Asignado ($)</label>
                    <input type="number" name="proyectos[${projectIndex}][sueldo]" class="form-control" min="0" value="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label font-mono text-white-50" style="font-size: 0.65rem;">Importancia / Rol Crítico</label>

                    <div class="custom-dropdown">
                        <input type="hidden" name="proyectos[${projectIndex}][importancia]" id="proy_imp_${projectIndex}" value="Media" required>
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
        if(slot) {
            slot.style.opacity = '0';
            slot.style.transform = 'scale(0.95)';
            setTimeout(() => {
                slot.remove();
            }, 300);
        }
    }
</script>
@endsection
