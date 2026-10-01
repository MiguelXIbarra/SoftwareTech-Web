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
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 20% 20%, rgba(6, 182, 212, 0.05) 0%, transparent 50%);
        z-index: 1;
        pointer-events: none;
    }

    .admin-container {
        position: relative;
        z-index: 5;
        width: 100%;
    }

    .kanban-filter-card {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(6, 182, 212, 0.2);
        backdrop-filter: blur(20px) saturate(160%);
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        position: relative;
        z-index: 50;
        width: 100%;
        box-sizing: border-box;
    }

    .kanban-filter-input {
        background: rgba(3, 7, 18, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
        border-radius: 8px !important;
        font-size: 0.85rem !important;
        padding: 10px 14px !important;
        transition: all 0.25s ease !important;
        width: 100%;
    }

    .kanban-filter-input::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    .kanban-filter-input:focus {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.3) !important;
        outline: none !important;
    }

    .custom-dropdown {
        position: relative;
        width: 100%;
    }

    .dropdown-trigger {
        background: rgba(3, 7, 18, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
        font-size: 0.85rem;
        border-radius: 8px;
        padding: 10px 36px 10px 14px;
        width: 100%;
        text-align: left;
        cursor: pointer;
        position: relative;
        transition: all 0.25s ease;
        user-select: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
        right: 14px;
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
        background: #030712 !important;
        border: 1px solid rgba(6, 182, 212, 0.4) !important;
        backdrop-filter: blur(20px);
        border-radius: 8px;
        margin: 0;
        padding: 6px 0;
        list-style: none;
        z-index: 99999 !important;
        max-height: 250px;
        overflow-y: auto;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.9), 0 0 20px rgba(6, 182, 212, 0.2);
    }

    .custom-dropdown.open .dropdown-menu-custom {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-menu-custom li {
        padding: 10px 14px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .dropdown-menu-custom li:hover {
        background: rgba(6, 182, 212, 0.2) !important;
        color: #00d4ff !important;
        padding-left: 18px;
    }

    .dropdown-menu-custom li.selected {
        background: rgba(6, 182, 212, 0.3) !important;
        color: #00d4ff !important;
        font-weight: 700;
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

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    .btn-clear-filters {
        background: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #f87171;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 16px;
        width: 100%;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 41px;
    }

    .btn-clear-filters:hover {
        background: rgba(239, 68, 68, 0.15);
        border-color: #ef4444;
        color: #ffffff;
        box-shadow: 0 0 12px rgba(239, 68, 68, 0.3);
    }

    .fifa-split-container {
        display: flex;
        gap: 24px;
        width: 100%;
        position: relative;
        align-items: start;
        z-index: 10;
    }

    .fifa-list-side {
        flex: 1;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .fifa-split-container.active .fifa-list-side {
        flex: 0 0 58% !important;
        max-width: 58% !important;
    }

    .fifa-detail-side {
        flex: 0 0 0%;
        opacity: 0;
        visibility: hidden;
        overflow: hidden;
        transform: translateX(30px);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .fifa-split-container.active .fifa-detail-side {
        flex: 0 0 38% !important;
        max-width: 38% !important;
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    .table-responsive-neon {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(6, 182, 212, 0.25) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6) !important;
    }

    .table-neon {
        width: 100%;
        margin-bottom: 0;
        color: #ffffff;
        border-collapse: separate;
        border-spacing: 0 12px;
    }

    .table-neon thead,
    .table-neon thead tr,
    .table-neon th {
        background: transparent !important;
        background-color: transparent !important;
        color: #22d3ee !important;
        border: none !important;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 12px 20px;
    }

    .table-neon td {
        background: rgba(255, 255, 255, 0.01);
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 16px 20px;
        vertical-align: middle;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .table-neon tr.selected td {
        background: rgba(6, 182, 212, 0.08) !important;
        border-color: rgba(6, 182, 212, 0.5) !important;
    }

    .table-neon tr td:first-child {
        border-left: 1px solid rgba(255, 255, 255, 0.05);
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .table-neon tr td:last-child {
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .role-badge {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 6px;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .role-superadmin { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; }
    .role-admin { background: rgba(249, 115, 22, 0.1); border: 1px solid rgba(249, 115, 22, 0.3); color: #fb923c; }
    .role-empleado { background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3); color: #22d3ee; }

    .detail-card-neon {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid #06b6d4 !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.2) !important;
        border-radius: 16px;
        padding: 32px;
        position: relative;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .meta-row:last-child {
        border-bottom: none;
    }

    .meta-key {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.5);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .meta-val {
        font-size: 0.9rem;
        font-weight: 600;
        color: #ffffff;
    }

    .projects-slider-container {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        margin-top: 12px;
        overflow: hidden;
        padding: 0 4px;
    }

    .projects-viewport-wrapper {
        width: 100%;
        overflow: hidden;
        transition: width 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .projects-track-neon {
        display: flex;
        gap: 16px;
        width: 100%;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .project-mini-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 16px;
        flex: 0 0 100%;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }

    .slider-arrow-btn {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 32px;
        background: rgba(6, 182, 212, 0.02);
        border: 1px solid rgba(6, 182, 212, 0.25);
        border-radius: 6px;
        color: #22d3ee;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .slider-arrow-btn.prev { left: -40px; transform: translateX(-10px); }
    .slider-arrow-btn.next { right: -40px; transform: translateX(10px); }

    .slider-arrow-btn:hover {
        background: rgba(6, 182, 212, 0.15);
        border-color: #06b6d4;
        color: #ffffff;
        box-shadow: 0 0 12px rgba(6, 182, 212, 0.3);
    }

    .projects-slider-container.has-arrows:hover .projects-viewport-wrapper {
        width: calc(100% - 84px);
        margin: 0 auto;
    }

    .projects-slider-container.has-arrows:hover .slider-arrow-btn.prev {
        opacity: 1;
        visibility: visible;
        left: 0;
        transform: translateX(0);
    }

    .projects-slider-container.has-arrows:hover .slider-arrow-btn.next {
        opacity: 1;
        visibility: visible;
        right: 0;
        transform: translateX(0);
    }

    .capacity-bar-container {
        background: rgba(255, 255, 255, 0.05);
        height: 6px;
        border-radius: 10px;
        overflow: hidden;
        width: 120px;
    }

    .capacity-bar {
        height: 100%;
        border-radius: 10px;
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

    .hud-control-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        width: 100%;
        gap: 16px;
    }

    .btn-hud-edit {
        flex: 1;
        text-align: center;
        background: rgba(6, 182, 212, 0.02);
        border: 1px solid rgba(6, 182, 212, 0.4);
        color: #22d3ee;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .btn-hud-edit:hover {
        color: #ffffff;
        background: rgba(6, 182, 212, 0.15);
        border-color: #06b6d4;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.25);
    }

    .hud-control-container form {
        flex: 1;
        display: flex;
    }

    .btn-hud-delete {
        flex: 1;
        text-align: center;
        background: rgba(239, 68, 68, 0.02);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #f87171;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        background-color: transparent;
        transition: all 0.25s ease;
        width: 100%;
    }

    .btn-hud-delete:hover {
        color: #ffffff;
        background: rgba(239, 68, 68, 0.1);
        border-color: #ef4444;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.25);
    }

    /* MODAL HUD PERSONALIZADO SIN DEPENDENCIAS */
    .custom-hud-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(3, 7, 18, 0.88);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        z-index: 999999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        box-sizing: border-box;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
    }

    .custom-hud-modal-backdrop.show {
        opacity: 1;
        visibility: visible;
    }

    .custom-hud-modal-card {
        background: rgba(11, 15, 25, 0.98);
        border: 1px solid rgba(167, 139, 250, 0.35);
        border-radius: 20px;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.9), 0 0 35px rgba(123, 104, 238, 0.2);
        width: 100%;
        max-width: 780px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform: scale(0.95) translateY(-10px);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        color: #ffffff;
    }

    .custom-hud-modal-backdrop.show .custom-hud-modal-card {
        transform: scale(1) translateY(0);
    }

    .custom-hud-modal-header {
        padding: 24px 28px 16px 28px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
    }

    .custom-hud-modal-close {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.7);
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .custom-hud-modal-close:hover {
        background: rgba(239, 68, 68, 0.15);
        border-color: #ef4444;
        color: #ffffff;
    }

    /* CYBER CHECKBOXES */
    .hud-cyber-check {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        cursor: pointer;
        user-select: none;
        margin: 0;
    }

    .hud-cyber-check input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .hud-cyber-check-box {
        position: relative;
        height: 18px;
        width: 18px;
        background: rgba(15, 23, 42, 0.9);
        border: 1.5px solid rgba(167, 139, 250, 0.4);
        border-radius: 5px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hud-cyber-check:hover input ~ .hud-cyber-check-box {
        border-color: #a78bfa;
        box-shadow: 0 0 10px rgba(167, 139, 250, 0.35);
    }

    .hud-cyber-check input:checked ~ .hud-cyber-check-box {
        background: linear-gradient(135deg, #7b68ee 0%, #a78bfa 100%);
        border-color: #c4b5fd;
        box-shadow: 0 0 12px rgba(167, 139, 250, 0.5);
    }

    .hud-cyber-check-box::after {
        content: "";
        position: absolute;
        display: none;
        left: 5px;
        top: 2px;
        width: 5px;
        height: 9px;
        border: solid #ffffff;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .hud-cyber-check input:checked ~ .hud-cyber-check-box::after {
        display: block;
    }

    /* CUSTOM HUD ROLE DROPDOWN */
    .hud-role-dropdown {
        position: relative;
        width: 100%;
        min-width: 175px;
    }

    .hud-role-trigger {
        background: rgba(15, 23, 42, 0.95);
        border: 1px solid rgba(167, 139, 250, 0.35);
        color: #f1f5f9;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        text-align: left;
    }

    .hud-role-trigger:hover, .hud-role-dropdown.open .hud-role-trigger {
        border-color: #a78bfa;
        background: rgba(20, 29, 52, 0.98);
        box-shadow: 0 0 12px rgba(167, 139, 250, 0.35);
    }

    .hud-role-menu {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        width: 100%;
        background: rgba(11, 15, 25, 0.98);
        border: 1px solid rgba(167, 139, 250, 0.45);
        backdrop-filter: blur(20px);
        border-radius: 8px;
        margin: 0;
        padding: 4px;
        list-style: none;
        z-index: 99999;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.9), 0 0 20px rgba(123, 104, 238, 0.25);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-6px);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .hud-role-dropdown.open .hud-role-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .hud-role-option {
        padding: 8px 12px;
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.78rem;
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: all 0.15s ease;
    }

    .hud-role-option:hover {
        background: rgba(123, 104, 238, 0.25);
        color: #ffffff;
        padding-left: 14px;
    }

    .hud-role-option.selected {
        background: rgba(123, 104, 238, 0.35);
        color: #c4b5fd;
        font-weight: 700;
    }
</style>

<div class="admin-viewport">
    <div class="container admin-container">

        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold text-white mb-1" style="font-size: 2.2rem; letter-spacing: -0.5px; font-weight: 800;">
                        Gestión de Equipo
                    </h2>
                    <span style="font-size: 0.75rem; color: #cbd5e1; letter-spacing: 1px; text-transform: uppercase;">
                        Panel de Personal e Integridad de Cargas
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="action-hud-btn" style="background: rgba(123, 104, 238, 0.12); border-color: rgba(167, 139, 250, 0.4); color: #c4b5fd;" onclick="openClickUpSyncModal()">
                        <i class="fas fa-sync-alt me-1"></i> Importar de ClickUp
                    </button>
                    <a href="{{ route('admin.equipo.crear') }}" class="action-hud-btn">
                        + Registrar Nuevo Miembro
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-dark border-info text-white d-flex align-items-center justify-content-between p-3 mb-4" style="background: rgba(6, 182, 212, 0.08); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px;">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-check-circle text-info" style="font-size: 1.2rem;"></i>
                    <div>
                        <div class="fw-bold">{{ session('success') }}</div>
                        @if(session('activation_link'))
                            <div class="small text-white-50 mt-1">
                                Enlace generado para <strong>{{ session('user_name') }}</strong>:
                                <span class="font-mono text-info">{{ session('activation_link') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @if(session('activation_link'))
                    <button type="button" class="btn btn-sm btn-info text-white font-mono" onclick="navigator.clipboard.writeText('{{ session('activation_link') }}'); alert('Enlace copiado al portapapeles.');">
                        <i class="fas fa-copy me-1"></i> Copiar Enlace
                    </button>
                @endif
            </div>
        @endif

        @php
            $dynamicRoles = $miembros->pluck('role')->unique()->filter()->values();
            $allProjects = \App\Models\Project::all();
            $maxProjectsCount = max($allProjects->count(), 5);
            $dynamicProjectsCount = range(0, $maxProjectsCount);
        @endphp

        <div class="kanban-filter-card">
            <div class="d-flex align-items-center gap-2 flex-wrap flex-md-nowrap">
                <div style="flex: 1.8; min-width: 140px;">
                    <input type="text" id="filterSearch" class="form-control kanban-filter-input" placeholder="Nombre / Email" onkeyup="applyFilters()">
                </div>
                <div style="flex: 1.2; min-width: 120px;">
                    <div class="custom-dropdown">
                        <input type="hidden" id="filterRol" value="">
                        <div class="dropdown-trigger" onclick="toggleDropdown(this)">Todos los roles</div>
                        <ul class="dropdown-menu-custom">
                            <li data-value="" class="selected" onclick="selectFilterOption(this, 'filterRol', 'Todos los roles')">Todos los roles</li>
                            @foreach($dynamicRoles as $role)
                                <li data-value="{{ strtolower($role) }}" onclick="selectFilterOption(this, 'filterRol', '{{ ucfirst($role) }}')">{{ ucfirst($role) }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div style="width: 75px; flex: 0 0 75px;">
                    <input type="number" id="filterEdad" class="form-control kanban-filter-input text-center" placeholder="Edad" onkeyup="applyFilters()" onchange="applyFilters()">
                </div>
                <div style="flex: 0.9; min-width: 105px;">
                    <div class="custom-dropdown">
                        <input type="hidden" id="filterProyectosCount" value="">
                        <div class="dropdown-trigger" onclick="toggleDropdown(this)">Cualquier cantidad</div>
                        <ul class="dropdown-menu-custom">
                            <li data-value="" class="selected" onclick="selectFilterOption(this, 'filterProyectosCount', 'Cualquier cantidad')">Cualquier cantidad</li>
                            @foreach($dynamicProjectsCount as $count)
                                <li data-value="{{ $count }}" onclick="selectFilterOption(this, 'filterProyectosCount', '{{ $count }} {{ $count == 1 ? 'proyecto' : 'proyectos' }}')">{{ $count }} {{ $count == 1 ? 'proyecto' : 'proyectos' }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div style="flex: 1.5; min-width: 130px;">
                    <div class="custom-dropdown">
                        <input type="hidden" id="filterProyecto" value="">
                        <div class="dropdown-trigger" onclick="toggleDropdown(this)">Todos los proyectos</div>
                        <ul class="dropdown-menu-custom">
                            <li data-value="" class="selected" onclick="selectFilterOption(this, 'filterProyecto', 'Todos los proyectos')">Todos los proyectos</li>
                            @foreach($allProjects as $proj)
                                <li data-value="{{ strtolower($proj->nombre) }}" onclick="selectFilterOption(this, 'filterProyecto', '{{ $proj->nombre }}')">{{ $proj->nombre }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div style="width: 50px; flex: 0 0 50px;">
                    <button type="button" class="btn-clear-filters px-2" onclick="resetFilters()" title="Limpiar Filtros">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="fifaSplitWrapper" class="fifa-split-container">

            <div class="fifa-list-side">
                <div class="table-responsive-neon">
                    <table class="table table-neon">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Rol</th>
                                <th class="hide-on-active">Email</th>
                            </tr>
                        </thead>
                        <tbody id="teamTableBody">
                            @foreach($miembros as $miembro)
                                @php
                                    $proyectosLiderados = $miembro->proyectosLiderados ?? collect([]);
                                    $esLider = $proyectosLiderados->count() > 0;
                                    $nombresLiderados = $proyectosLiderados->pluck('nombre')->toArray();

                                    // Combinar proyectos donde está en equipo o es líder asignado
                                    $todosProyectos = $miembro->proyectos->concat($proyectosLiderados)->unique('id');
                                    $numProyectos = $todosProyectos->count();
                                    $proyectosArray = [];

                                    foreach($todosProyectos->take(3) as $p) {
                                        $esLiderDeEste = ($p->developer_id == $miembro->id);
                                        $proyectosArray[] = [
                                            'nombre'      => $p->nombre,
                                            'descripcion' => $p->descripcion ?? 'Sin descripción disponible',
                                            'importancia' => $p->pivot->importancia ?? ($esLiderDeEste ? 'Crítica' : 'Media'),
                                            'sueldo'      => $p->pivot->sueldo_proyecto ?? 0,
                                            'es_lider'    => $esLiderDeEste
                                        ];
                                    }

                                    $edad = $miembro->corporation->edad ?? 'Por definir';
                                    $capacity = $miembro->corporation->capacity ?? 0;

                                    $capacityColor = '#22d3ee';
                                    $capacityStatus = 'Disponible';

                                    if ($capacity >= 40) {
                                        $capacityColor = '#ef4444';
                                        $capacityStatus = 'Saturado';
                                    } elseif ($capacity >= 20) {
                                        $capacityColor = '#facc15';
                                        $capacityStatus = 'Ocupado';
                                    }

                                    $linkActivacion = $miembro->activation_token ? route('portal.activate.form', $miembro->activation_token) : null;
                                @endphp
                                <tr class="team-row" onclick="selectPlayer(this)" data-player-info="{{ json_encode([
                                    'id' => $miembro->id,
                                    'nombre' => $miembro->name,
                                    'edad' => $edad,
                                    'email' => $miembro->email,
                                    'rol' => $miembro->role,
                                    'active' => (int) $miembro->active,
                                    'clickup_user_id' => $miembro->clickup_user_id,
                                    'activation_token' => $miembro->activation_token,
                                    'activation_url' => $linkActivacion,
                                    'es_lider' => $esLider,
                                    'proyectos_liderados' => implode(', ', $nombresLiderados),
                                    'proyectos_count' => $numProyectos,
                                    'proyectos' => $proyectosArray,
                                    'capacity' => $capacity,
                                    'capacity_color' => $capacityColor,
                                    'capacity_status' => $capacityStatus
                                    ]) }}">
                                    <td>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="fw-bold text-white" style="font-size: 0.95rem;">{{ $miembro->name }}</span>
                                            @if($esLider)
                                                <span class="badge font-mono" style="background: rgba(250, 204, 21, 0.15); border: 1px solid rgba(250, 204, 21, 0.45); color: #facc15; font-size: 0.68rem; padding: 2px 7px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;" title="Líder asignado de proyecto">
                                                    <i class="fas fa-crown text-warning" style="font-size: 0.65rem;"></i> Líder
                                                </span>
                                            @endif
                                            @if($miembro->active == 0)
                                                <span class="badge font-mono" style="background: rgba(250, 204, 21, 0.1); border: 1px solid rgba(250, 204, 21, 0.3); color: #facc15; font-size: 0.65rem; padding: 2px 6px; border-radius: 4px;" title="Cuenta pendiente de activación">
                                                    <i class="fas fa-clock"></i> Pendiente
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="role-badge role-{{ $miembro->role }}">{{ $miembro->role }}</span>
                                    </td>
                                    <td class="hide-on-active">
                                        <span style="color: rgba(255,255,255,0.6); font-size: 0.85rem;">{{ $miembro->email }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="fifa-detail-side">
                <div class="detail-card-neon">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <span id="detRol" class="role-badge mb-2"></span>
                            <h3 id="detNombre" class="fw-bold text-white mb-0" style="font-size: 1.6rem; letter-spacing: -0.5px;"></h3>
                        </div>
                        <button onclick="closeDetails()" class="btn-logout" style="border: none; background: rgba(255,255,255,0.05); color: #fff; padding: 6px 12px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="meta-row">
                        <span class="meta-key">Edad</span>
                        <span id="detEdad" class="meta-val"></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">Email Terminal</span>
                        <span id="detEmail" class="meta-val text-white-50" style="font-size: 0.8rem;"></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">ClickUp ID</span>
                        <span id="detClickUpId" class="meta-val font-mono" style="font-size: 0.8rem; color: #a78bfa;"></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">Proyectos Activos</span>
                        <span id="detProyectosCount" class="meta-val"></span>
                    </div>

                    <div class="meta-row">
                        <span class="meta-key">Carga Operativa</span>
                        <div class="d-flex align-items-center gap-3">
                            <span id="detCapacityStatus" class="fw-bold" style="font-size: 0.75rem; text-transform: uppercase;"></span>
                            <div class="capacity-bar-container">
                                <div id="detCapacityBar" class="capacity-bar"></div>
                            </div>
                            <span id="detCapacityPct" class="text-white-50" style="font-size: 0.75rem;"></span>
                        </div>
                    </div>

                    <!-- PANEL DE ACTIVACIÓN SI ESTÁ PENDIENTE -->
                    <div id="detActivationContainer" style="display: none; background: rgba(250, 204, 21, 0.05); border: 1px solid rgba(250, 204, 21, 0.3); border-radius: 12px; padding: 14px; margin-top: 15px;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span style="color: #facc15; font-size: 0.78rem; font-weight: 700;">
                                <i class="fas fa-envelope-open-text me-1"></i> Cuenta Pendiente de Activación
                            </span>
                            <span class="badge bg-warning text-dark font-mono" style="font-size: 0.6rem;">Sin contraseña</span>
                        </div>
                        <p style="color: rgba(255,255,255,0.65); font-size: 0.73rem; margin-bottom: 10px; line-height: 1.3;">
                            El usuario fue importado pero no ha establecido su contraseña. Puedes enviarle el correo de activación o copiar su enlace directo.
                        </p>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" id="btnSendActivationMail" class="btn btn-sm btn-info text-white font-mono" style="font-size: 0.72rem; border-radius: 6px; padding: 5px 12px;" onclick="sendActivationFromDetails()">
                                <i class="fas fa-paper-plane me-1"></i> Enviar Correo de Activación
                            </button>
                            <button type="button" id="btnCopyActivationLink" class="btn btn-sm btn-outline-light font-mono" style="font-size: 0.72rem; border-radius: 6px; padding: 5px 12px;" onclick="copyActivationFromDetails()">
                                <i class="fas fa-copy me-1"></i> Copiar Enlace
                            </button>
                        </div>
                    </div>

                    <h4 class="fw-bold text-white mt-4 mb-2" style="font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; color: #22d3ee;">
                        Proyectos Asignados (Max 3)
                    </h4>

                    <div id="fifaSliderContainer" class="projects-slider-container">
                        <button type="button" class="slider-arrow-btn prev" onclick="moveSlider(-1)">
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        <div class="projects-viewport-wrapper">
                            <div id="detProyectosTrack" class="projects-track-neon"></div>
                        </div>

                        <button type="button" class="slider-arrow-btn next" onclick="moveSlider(1)">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="hud-control-container">
                    <a href="#" id="hudEditLink" class="btn-hud-edit">Editar Registro</a>

                    <form action="#" method="POST" id="hudDeleteForm" onsubmit="return confirm('[WARNING]: ¿Proceder con el purgado de esta identidad?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-hud-delete">Eliminar Miembro</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    let currentSlideIndex = 0;
    let maxSlidesCount = 0;

    function toggleDropdown(triggerElement) {
        const parent = triggerElement.parentElement;
        document.querySelectorAll('.custom-dropdown').forEach(dd => {
            if (dd !== parent) dd.classList.remove('open');
        });
        parent.classList.toggle('open');
    }

    function selectFilterOption(liElement, hiddenInputId, label) {
        const value = liElement.getAttribute('data-value');
        const parent = liElement.closest('.custom-dropdown');

        document.getElementById(hiddenInputId).value = value;
        parent.querySelector('.dropdown-trigger').innerText = label;

        parent.querySelectorAll('li').forEach(item => item.classList.remove('selected'));
        liElement.classList.add('selected');
        parent.classList.remove('open');

        applyFilters();
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.custom-dropdown').forEach(dd => dd.classList.remove('open'));
        }
    });

    function applyFilters() {
        const searchVal = document.getElementById('filterSearch').value.toLowerCase().trim();
        const rolVal = document.getElementById('filterRol').value.toLowerCase().trim();
        const edadVal = document.getElementById('filterEdad').value.trim();
        const proyectosCountVal = document.getElementById('filterProyectosCount').value.trim();
        const proyectoVal = document.getElementById('filterProyecto').value.toLowerCase().trim();

        const rows = document.querySelectorAll('.team-row');

        rows.forEach(row => {
            const data = JSON.parse(row.getAttribute('data-player-info'));
            let showRow = true;

            if (searchVal && !data.nombre.toLowerCase().includes(searchVal) && !data.email.toLowerCase().includes(searchVal)) {
                showRow = false;
            }

            if (rolVal && data.rol.toLowerCase() !== rolVal) {
                showRow = false;
            }

            if (edadVal && String(data.edad) !== edadVal) {
                showRow = false;
            }

            if (proyectosCountVal !== "" && String(data.proyectos_count) !== proyectosCountVal) {
                showRow = false;
            }

            if (proyectoVal) {
                const hasProject = data.proyectos.some(p => p.nombre.toLowerCase().includes(proyectoVal));
                if (!hasProject) showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('filterSearch').value = '';
        document.getElementById('filterEdad').value = '';

        document.getElementById('filterRol').value = '';
        document.getElementById('filterProyectosCount').value = '';
        document.getElementById('filterProyecto').value = '';

        const dropdowns = document.querySelectorAll('.kanban-filter-card .custom-dropdown');
        const defaultLabels = ['Todos los roles', 'Cualquier cantidad', 'Todos los proyectos'];

        dropdowns.forEach((dd, idx) => {
            dd.querySelector('.dropdown-trigger').innerText = defaultLabels[idx];
            dd.querySelectorAll('li').forEach((li, liIdx) => {
                if(liIdx === 0) li.classList.add('selected');
                else li.classList.remove('selected');
            });
        });

        applyFilters();
    }

    function selectPlayer(rowElement) {
        const rows = document.querySelectorAll('.table-neon tr');
        rows.forEach(r => r.classList.remove('selected'));
        rowElement.classList.add('selected');

        let data;
        try {
            data = JSON.parse(rowElement.getAttribute('data-player-info'));
        } catch (e) {
            console.error("Error al parsear información del miembro:", e);
            return;
        }

        document.getElementById('detNombre').innerText = data.nombre || 'Sin nombre';
        document.getElementById('detEdad').innerText = (data.edad && data.edad !== 'Por definir') ? `${data.edad} años` : 'Por definir';
        document.getElementById('detEmail').innerText = data.email || 'Sin email';
        document.getElementById('detProyectosCount').innerText = data.proyectos_count || 0;

        // CLICKUP ID
        const detClickUp = document.getElementById('detClickUpId');
        if (detClickUp) {
            detClickUp.innerText = data.clickup_user_id ? `#${data.clickup_user_id}` : 'No vinculado';
        }

        // PANEL DE ACTIVACIÓN (SI LA CUENTA NO ESTÁ ACTIVA)
        const actContainer = document.getElementById('detActivationContainer');
        if (actContainer) {
            if (data.active === 0 || data.activation_token) {
                actContainer.style.display = 'block';
                actContainer.setAttribute('data-user-id', data.id);
                actContainer.setAttribute('data-activation-url', data.activation_url || '');
            } else {
                actContainer.style.display = 'none';
            }
        }

        // BADGE DE ROL PROTEGIDO CONTRA UNDEFINED
        const badge = document.getElementById('detRol');
        const rawRole = (data.rol || 'empleado').toLowerCase();

        badge.className = 'role-badge mb-2 role-' + rawRole;

        if (rawRole === 'superadmin') {
            badge.innerText = 'SUPERADMIN';
        } else if (rawRole === 'admin') {
            badge.innerText = 'ADMIN';
        } else {
            badge.innerText = 'EMPLEADO';
        }

        // CARGA OPERATIVA
        let capColor = '#22d3ee';
        let capStatus = 'Disponible';

        const capacity = parseInt(data.capacity) || 0;

        if (capacity >= 40) {
            capColor = '#ef4444';
            capStatus = 'Saturado';
        } else if (capacity >= 20) {
            capColor = '#facc15';
            capStatus = 'Ocupado';
        }

        const detCapStatus = document.getElementById('detCapacityStatus');
        if (detCapStatus) {
            detCapStatus.innerText = capStatus;
            detCapStatus.style.color = capColor;
        }

        const capacityPercentage = Math.min((capacity / 40) * 100, 100);
        const bar = document.getElementById('detCapacityBar');
        if (bar) {
            bar.style.width = capacityPercentage + '%';
            bar.style.backgroundColor = capColor;
            bar.style.boxShadow = `0 0 8px ${capColor}`;
        }

        const detCapPct = document.getElementById('detCapacityPct');
        if (detCapPct) detCapPct.innerText = capacity + ' hrs/sem';

        // ENLACES DE ACCIÓN
        let editUrl = "{{ route('admin.equipo.editar', ':id') }}";
        let deleteUrl = "{{ route('admin.equipo.destroy', ':id') }}";

        document.getElementById('hudEditLink').href = editUrl.replace(':id', data.id);
        document.getElementById('hudDeleteForm').action = deleteUrl.replace(':id', data.id);

        // SLIDER DE PROYECTOS
        const trackContainer = document.getElementById('detProyectosTrack');
        const sliderContainer = document.getElementById('fifaSliderContainer');
        trackContainer.innerHTML = '';
        currentSlideIndex = 0;

        const proyectosList = data.proyectos || [];
        maxSlidesCount = proyectosList.length;

        trackContainer.style.transform = 'translateX(0px)';

        if (maxSlidesCount > 1) {
            sliderContainer.classList.add('has-arrows');
            document.querySelector('.slider-arrow-btn.prev').style.display = 'flex';
            document.querySelector('.slider-arrow-btn.next').style.display = 'flex';
        } else {
            sliderContainer.classList.remove('has-arrows');
            document.querySelector('.slider-arrow-btn.prev').style.display = 'none';
            document.querySelector('.slider-arrow-btn.next').style.display = 'none';
        }

        if (maxSlidesCount === 0) {
            trackContainer.innerHTML = '<div class="project-mini-card text-center"><span class="text-white-50" style="font-size: 0.75rem;">Sin proyectos asignados</span></div>';
        } else {
            proyectosList.forEach(p => {
                let importanceColor = '#4ade80';
                const imp = p.importancia ? p.importancia.toLowerCase() : '';

                if (imp === 'media') {
                    importanceColor = '#facc15';
                } else if (imp === 'alta') {
                    importanceColor = '#fb923c';
                } else if (imp === 'crítica' || imp === 'critica') {
                    importanceColor = '#ef4444';
                }

                const leaderIconHtml = p.es_lider 
                    ? `<i class="fas fa-crown text-warning" title="Líder asignado del proyecto" style="font-size: 0.85rem; filter: drop-shadow(0 0 6px rgba(250, 204, 21, 0.7));"></i>`
                    : '';

                const miniCard = document.createElement('div');
                miniCard.className = 'project-mini-card';
                miniCard.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-white" style="font-size: 0.9rem;">${p.nombre || 'Sin nombre'}</span>
                            ${leaderIconHtml}
                        </div>
                        <span class="text-info fw-bold" style="font-size: 0.8rem;">$${Number(p.sueldo || 0).toLocaleString()} MXN</span>
                    </div>
                    <p class="text-white-50 mb-2" style="font-size: 0.75rem; line-height: 1.2;">
                        ${p.descripcion || 'Sin descripción disponible'}
                    </p>
                    <div class="d-flex justify-content-between" style="font-size: 0.75rem;">
                        <span style="color: rgba(255,255,255,0.35);">IMPORTANCIA / NODO:</span>
                        <span style="color: ${importanceColor}; font-weight: 600;">${p.importancia || 'Media'}</span>
                    </div>
                `;
                trackContainer.appendChild(miniCard);
            });
        }

        const container = document.getElementById('fifaSplitWrapper');
        if (!container.classList.contains('active')) {
            container.classList.add('active');

            const hideTargets = document.querySelectorAll('.hide-on-active');
            hideTargets.forEach(el => {
                el.style.display = 'none';
            });
        }
    }

    function moveSlider(direction) {
        if (maxSlidesCount <= 1) return;

        currentSlideIndex += direction;

        if (currentSlideIndex < 0) {
            currentSlideIndex = maxSlidesCount - 1;
        } else if (currentSlideIndex >= maxSlidesCount) {
            currentSlideIndex = 0;
        }

        const track = document.getElementById('detProyectosTrack');
        const cardWidth = track.querySelector('.project-mini-card').offsetWidth;

        const offset = currentSlideIndex * (cardWidth + 16);
        track.style.transform = `translateX(-${offset}px)`;
    }

    function closeDetails() {
        const container = document.getElementById('fifaSplitWrapper');
        container.classList.remove('active');

        const rows = document.querySelectorAll('.table-neon tr');
        rows.forEach(r => r.classList.remove('selected'));

        setTimeout(() => {
            const hideTargets = document.querySelectorAll('.hide-on-active');
            hideTargets.forEach(el => {
                el.style.display = 'table-cell';
            });
        }, 400);
    }

    // ENVÍO DE CORREO DE ACTIVACIÓN DESDE DETALLES
    function sendActivationFromDetails() {
        const container = document.getElementById('detActivationContainer');
        const userId = container.getAttribute('data-user-id');
        const btn = document.getElementById('btnSendActivationMail');

        if (!userId) return;

        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enviando...';
        btn.disabled = true;

        fetch(`{{ url('/console/equipo') }}/${userId}/send-activation`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            if (data.success) {
                container.setAttribute('data-activation-url', data.activation_url);
                cyberAlert.success(data.message, '¡Correo Enviado!');
            } else {
                cyberAlert.error(data.message || 'Ocurrió un error al enviar el correo.', 'Fallo en Envío');
            }
        })
        .catch(err => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            cyberAlert.error('Error de conexión al enviar el correo.', 'Error de Conexión');
        });
    }

    function copyActivationFromDetails() {
        const container = document.getElementById('detActivationContainer');
        let url = container.getAttribute('data-activation-url');

        if (url) {
            navigator.clipboard.writeText(url);
            cyberAlert.success('Enlace de activación copiado al portapapeles:\n' + url, '¡Enlace Copiado!');
        } else {
            // Solicitar al backend generar el enlace
            sendActivationFromDetails();
        }
    }

    // MODAL DE IMPORTACIÓN CLICKUP (STANDALONE VANILLA JS)
    let clickupAvailableUsers = [];

    function openClickUpSyncModal() {
        const modal = document.getElementById('clickupSyncModal');
        if (!modal) return;
        
        modal.style.display = 'flex';
        // Trigger reflow for CSS transition
        void modal.offsetWidth;
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        document.getElementById('clickupLoadingSpinner').style.display = 'block';
        document.getElementById('clickupContentArea').style.display = 'none';

        fetch("{{ route('admin.equipo.clickup.preview') }}", {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('clickupLoadingSpinner').style.display = 'none';
            document.getElementById('clickupContentArea').style.display = 'block';

            if (!data.success) {
                cyberAlert.error(data.message || 'Error al conectar con ClickUp.', 'ClickUp API');
                closeClickUpSyncModal();
                return;
            }

            clickupAvailableUsers = data.available || [];
            renderClickUpPreview(data);
        })
        .catch(err => {
            document.getElementById('clickupLoadingSpinner').style.display = 'none';
            cyberAlert.error('No se pudo conectar con el servidor para consultar ClickUp.', 'Error de Red');
            closeClickUpSyncModal();
        });
    }

    function closeClickUpSyncModal() {
        const modal = document.getElementById('clickupSyncModal');
        if (!modal) return;
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 250);
    }

    function handleClickUpModalBackdrop(e) {
        if (e.target.id === 'clickupSyncModal') {
            closeClickUpSyncModal();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('clickupSyncModal');
            if (modal && modal.classList.contains('show')) {
                closeClickUpSyncModal();
            }
        }
    });

    function renderClickUpPreview(data) {
        const tbody = document.getElementById('clickupUsersTableBody');
        const statsLabel = document.getElementById('clickupStatsLabel');
        const noUsersMsg = document.getElementById('clickupNoUsersMessage');
        const listDiv = document.getElementById('clickupAvailableList');
        const btnImport = document.getElementById('btnExecuteImport');

        tbody.innerHTML = '';
        statsLabel.innerText = `${data.available.length} nuevos colaboradores detectados en ClickUp (${data.already_in_portal.length} ya sincronizados)`;

        if (data.available.length === 0) {
            listDiv.style.display = 'none';
            noUsersMsg.style.display = 'block';
            btnImport.style.display = 'none';
            return;
        }

        listDiv.style.display = 'block';
        noUsersMsg.style.display = 'none';
        btnImport.style.display = 'inline-flex';

        data.available.forEach((u, idx) => {
            const tr = document.createElement('tr');
            tr.style.borderBottom = '1px solid rgba(255,255,255,0.06)';
            tr.innerHTML = `
                <td style="padding: 12px 10px; vertical-align: middle;">
                    <label class="hud-cyber-check">
                        <input type="checkbox" class="clickup-user-checkbox" data-idx="${idx}" checked>
                        <span class="hud-cyber-check-box"></span>
                    </label>
                </td>
                <td style="padding: 12px 10px; vertical-align: middle;">
                    <span class="fw-bold text-white">${u.name}</span>
                </td>
                <td style="padding: 12px 10px; vertical-align: middle;">
                    <span class="font-mono text-white-50 small">${u.email}</span>
                </td>
                <td style="padding: 12px 10px; vertical-align: middle;">
                    <span class="font-mono px-2 py-1 rounded" style="background: rgba(167, 139, 250, 0.1); color: #c4b5fd; font-size: 0.75rem; border: 1px solid rgba(167, 139, 250, 0.25);">#${u.clickup_id}</span>
                </td>
                <td style="padding: 12px 10px; vertical-align: middle;">
                    <div class="hud-role-dropdown" id="hudRoleDropdown_${idx}">
                        <input type="hidden" class="clickup-role-input" data-idx="${idx}" value="empleado">
                        <button type="button" class="hud-role-trigger" onclick="toggleRoleDropdown(${idx}, event)">
                            <span id="roleLabel_${idx}">Empleado</span>
                            <i class="fas fa-chevron-down ms-2" style="font-size: 0.65rem; color: #a78bfa;"></i>
                        </button>
                        <div class="hud-role-menu" id="roleMenu_${idx}">
                            <div class="hud-role-option selected" onclick="selectRoleOption(${idx}, 'empleado', 'Empleado', event)">
                                <i class="fas fa-user text-white-50 me-2" style="font-size: 0.75rem;"></i> Empleado
                            </div>
                            <div class="hud-role-option" onclick="selectRoleOption(${idx}, 'admin', 'Administrador', event)">
                                <i class="fas fa-user-shield text-warning me-2" style="font-size: 0.75rem;"></i> Administrador
                            </div>
                            <div class="hud-role-option" onclick="selectRoleOption(${idx}, 'superadmin', 'Superadministrador', event)">
                                <i class="fas fa-crown text-info me-2" style="font-size: 0.75rem;"></i> Superadministrador
                            </div>
                        </div>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function toggleRoleDropdown(idx, event) {
        event.stopPropagation();
        const currentDropdown = document.getElementById(`hudRoleDropdown_${idx}`);
        if (!currentDropdown) return;
        const isOpen = currentDropdown.classList.contains('open');

        document.querySelectorAll('.hud-role-dropdown').forEach(d => d.classList.remove('open'));

        if (!isOpen) {
            currentDropdown.classList.add('open');
        }
    }

    function selectRoleOption(idx, roleValue, roleLabel, event) {
        event.stopPropagation();
        const dropdown = document.getElementById(`hudRoleDropdown_${idx}`);
        if (!dropdown) return;
        const input = dropdown.querySelector('.clickup-role-input');
        const label = document.getElementById(`roleLabel_${idx}`);
        
        if (input) input.value = roleValue;
        if (label) label.innerText = roleLabel;

        dropdown.querySelectorAll('.hud-role-option').forEach(opt => opt.classList.remove('selected'));
        if (event.currentTarget) event.currentTarget.classList.add('selected');

        dropdown.classList.remove('open');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.hud-role-dropdown')) {
            document.querySelectorAll('.hud-role-dropdown').forEach(d => d.classList.remove('open'));
        }
    });

    function toggleSelectAllClickUp(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.clickup-user-checkbox');
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
    }

    function executeClickUpImport() {
        const checkboxes = document.querySelectorAll('.clickup-user-checkbox:checked');
        if (checkboxes.length === 0) {
            cyberAlert.warning('Por favor selecciona al menos un colaborador para importar.', 'Sin Selección');
            return;
        }

        const selectedUsers = [];
        checkboxes.forEach(cb => {
            const idx = parseInt(cb.getAttribute('data-idx'));
            const u = clickupAvailableUsers[idx];
            const roleInput = document.querySelector(`.clickup-role-input[data-idx="${idx}"]`);
            const role = roleInput ? roleInput.value : 'empleado';

            selectedUsers.push({
                name: u.name,
                email: u.email,
                clickup_id: u.clickup_id,
                role: role
            });
        });

        const sendEmails = document.getElementById('sendEmailsCheckbox').checked;
        const btn = document.getElementById('btnExecuteImport');
        const originalText = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Importando...';
        btn.disabled = true;

        fetch("{{ route('admin.equipo.clickup.import') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                users: selectedUsers,
                send_emails: sendEmails
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;

            if (data.success) {
                cyberAlert.success(data.message + (sendEmails ? '\nSe enviaron los correos con el enlace de activación.' : ''), '¡Importación Exitosa!', 4000, function() {
                    window.location.reload();
                });
            } else {
                cyberAlert.error(data.message || 'Error al importar usuarios.', 'Fallo en Importación');
            }
        })
        .catch(err => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            cyberAlert.error('Error al procesar la importación.', 'Error del Servidor');
        });
    }
</script>

<!-- MODAL DE IMPORTACIÓN CLICKUP (CUSTOM HUD) -->
<div id="clickupSyncModal" class="custom-hud-modal-backdrop" onclick="handleClickUpModalBackdrop(event)">
    <div class="custom-hud-modal-card" onclick="event.stopPropagation()">
        <!-- Encabezado -->
        <div class="custom-hud-modal-header">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span style="display: inline-block; width: 10px; height: 10px; background: #a78bfa; border-radius: 50%; box-shadow: 0 0 12px #a78bfa;"></span>
                    <h4 class="fw-bold text-white mb-0" style="font-size: 1.25rem; letter-spacing: -0.3px;">
                        Sincronizar e Importar Personal de ClickUp
                    </h4>
                </div>
                <p class="text-white-50 small mb-0" style="font-size: 0.8rem;">
                    Detecta colaboradores en tu Workspace de ClickUp que aún no están en el portal y genera sus cuentas con enlace de activación.
                </p>
            </div>
            <button type="button" class="custom-hud-modal-close" onclick="closeClickUpSyncModal()" title="Cerrar modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Cuerpo -->
        <div style="padding: 24px 28px; overflow-y: auto;">
            <div id="clickupLoadingSpinner" class="text-center py-5">
                <div class="spinner-border text-info mb-3" role="status" style="width: 2.5rem; height: 2.5rem; color: #a78bfa !important;"></div>
                <div class="text-white-50 font-mono small">Consultando Workspace de ClickUp vía API...</div>
            </div>

            <div id="clickupContentArea" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <span class="font-mono small" id="clickupStatsLabel" style="color: #a78bfa;"></span>
                    <div class="d-flex align-items-center gap-2" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); padding: 6px 12px; border-radius: 8px;">
                        <label class="hud-cyber-check">
                            <input type="checkbox" id="sendEmailsCheckbox" checked>
                            <span class="hud-cyber-check-box"></span>
                        </label>
                        <label for="sendEmailsCheckbox" class="text-white-50 small mb-0" style="cursor: pointer; font-size: 0.78rem;">
                            Enviar correo de activación automáticamente
                        </label>
                    </div>
                </div>

                <div id="clickupAvailableList" class="mb-3">
                    <div style="min-height: 200px; max-height: 320px; overflow-y: auto; overflow-x: visible; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; background: rgba(3,7,18,0.5); padding-bottom: 50px;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02); text-align: left;">
                                    <th style="padding: 10px; width: 40px;">
                                        <label class="hud-cyber-check">
                                            <input type="checkbox" id="selectAllClickUpUsers" onchange="toggleSelectAllClickUp(this)" checked>
                                            <span class="hud-cyber-check-box"></span>
                                        </label>
                                    </th>
                                    <th style="padding: 10px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8;">Colaborador</th>
                                    <th style="padding: 10px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8;">Email</th>
                                    <th style="padding: 10px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8;">ID ClickUp</th>
                                    <th style="padding: 10px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; width: 180px;">Rol Portal</th>
                                </tr>
                            </thead>
                            <tbody id="clickupUsersTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <div id="clickupNoUsersMessage" style="display: none; background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 24px; text-align: center;">
                    <i class="fas fa-check-double text-success mb-2" style="font-size: 1.8rem;"></i>
                    <div class="fw-bold text-white mb-1">¡Todo el personal de ClickUp ya está sincronizado!</div>
                    <div class="small text-white-50">No hay usuarios nuevos pendientes de importar en este momento.</div>
                </div>
            </div>
        </div>

        <!-- Pie / Botones -->
        <div style="padding: 16px 28px 24px 28px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; justify-content: space-between; align-items: center; gap: 12px;">
            <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-2" onclick="closeClickUpSyncModal()" style="border-radius: 8px; font-size: 0.8rem; color: #94a3b8; border-color: rgba(255,255,255,0.2);">
                Cerrar
            </button>
            <button type="button" id="btnExecuteImport" class="action-hud-btn" onclick="executeClickUpImport()" style="background: rgba(123, 104, 238, 0.2); border-color: #a78bfa; color: #ffffff; padding: 10px 20px; font-size: 0.8rem; display: none;">
                <i class="fas fa-file-import me-1"></i> Importar Seleccionados
            </button>
        </div>
    </div>
</div>
@endsection
