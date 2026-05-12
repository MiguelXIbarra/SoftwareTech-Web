@extends('layouts.page')
@section('title', 'Bandeja de Entrada')

@section('css')
<style>
    .email-card {
        background: rgba(255, 255, 255, 0.05) !important;
        border: none !important;
        border-radius: 8px;
        transition: all 0.25s ease;
    }

    .email-card:hover {
        background: rgba(255, 255, 255, 0.08) !important;
        transform: translateX(4px);
        border-left: 4px solid #4472f1 !important;
    }

    .filter-panel {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.06);
        box-shadow: none;
        padding: 0.25rem 0.5rem;
    }

    .filter-panel .card-body {
        padding: 0.6rem 0.4rem;
    }

    .filter-input {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.09);
        color: #e9ecef;
        min-height: 38px;
        padding: 0.45rem 0.75rem;
        border-radius: 10px;
    }

    .filter-input:focus {
        border-color: rgba(255, 255, 255, 0.18);
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.12);
    }

    .filter-label {
        display: block;
        margin-bottom: 0.18rem;
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.55);
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .action-container {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .btn-glow {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 6px 10px;
        border-radius: 6px;
        color: inherit;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
        text-decoration: none;
    }

    .btn-glow:hover {
        border-color: rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.05);
        transform: translateY(-2px);
    }

    .btn-redactar {
        background-color: #4472f1;
        border-radius: 20px;
        padding: 6px 20px;
        transition: all 0.25s ease;
    }

    .btn-redactar:hover {
        background-color: #355fd1;
        box-shadow: 0 0 20px rgba(68, 114, 241, 0.35);
        transform: scale(1.02);
    }
</style>
@endsection

@section('content_header')
<div class="d-flex justify-content-between align-items-center px-3">
    <h1 class="text-bold text-white">Bandeja de Entrada</h1>
    <a href="{{ route('emails.create') }}" class="btn text-white shadow-sm btn-redactar">
        <i class="fas fa-plus mr-1"></i> Redactar
    </a>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12 px-4">

        {{-- FILTROS Y BÚSQUEDA --}}
        <div class="card card-dark filter-panel mb-3">
            <div class="card-body">
                <form id="emails-filter-form" action="{{ route('emails.index') }}" method="GET"
                    class="row gx-2 gy-2 align-items-end">
                    <div class="col-md-4">
                        <label class="filter-label">Buscar</label>
                        <input type="text" autocomplete="off" name="q" value="{{ request('q') }}"
                            class="form-control filter-input" placeholder="Buscar asunto o texto">
                    </div>
                    <div class="col-md-3">
                        <label class="filter-label">Remitente</label>
                        <select name="sender_id" class="form-control filter-input">
                            <option value="">Todos</option>
                            @foreach($senders as $sender)
                            <option value="{{ $sender->id }}" {{ request('sender_id')==$sender->id ? 'selected' : '' }}>
                                {{ $sender->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label">Adjuntos</label>
                        <select name="has_attachment" class="form-control filter-input">
                            <option value="0" {{ request('has_attachment') ? '' : 'selected' }}>Todos</option>
                            <option value="1" {{ request('has_attachment') ? 'selected' : '' }}>Con adjuntos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label">Destacados</label>
                        <select name="important" class="form-control filter-input">
                            <option value="0" {{ request('important') ? '' : 'selected' }}>Todos</option>
                            <option value="1" {{ request('important') ? 'selected' : '' }}>Solo destacados</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        {{-- ENCABEZADOS --}}
        <div class="d-none d-md-flex row mb-2 px-3 text-muted small text-uppercase text-bold"
            style="letter-spacing: 1px;">
            <div class="col-md-1 text-center"></div>
            <div class="col-md-2">Remitente</div>
            <div class="col-md-5">Contenido</div>
            <div class="col-md-2 text-center">Fecha</div>
            <div class="col-md-2 text-right px-4">Acciones</div>
        </div>

        <div id="emails-list-container">
            @include('emails.partials.list')
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(formId) {
        Swal.fire({
            title: '¿Eliminar correo?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4472f1',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: '#1a222b',
            color: '#ffffff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

    const filterForm = document.getElementById('emails-filter-form');
    const emailListContainer = document.getElementById('emails-list-container');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function debounce(fn, delay = 250) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn(...args), delay);
        };
    }

    function updateEmailList() {
        const params = new URLSearchParams(new FormData(filterForm));
        const url = `${filterForm.action}?${params.toString()}`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.text())
            .then(html => {
                emailListContainer.innerHTML = html;
                window.history.replaceState({}, '', url);
            })
            .catch(error => {
                console.error('Error al cargar correos:', error);
            });
    }

    function toggleStar(event) {
        const button = event.target.closest('.star-toggle');
        if (!button) return;

        const url = button.dataset.url;
        if (!url) return;

        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.important === undefined) {
                    throw new Error(data.error || 'No se pudo cambiar el destacado');
                }

                const icon = button.querySelector('i');
                if (!icon) return;

                if (data.important) {
                    icon.classList.remove('far', 'text-muted');
                    icon.classList.add('fas', 'text-warning');
                } else {
                    icon.classList.remove('fas', 'text-warning');
                    icon.classList.add('far', 'text-muted');
                }
            })
            .catch(error => {
                console.error('Error al cambiar el destacado:', error);
            });
    }

    const debouncedUpdate = debounce(updateEmailList, 250);

    filterForm.querySelector('input[name="q"]').addEventListener('input', debouncedUpdate);
    filterForm.querySelectorAll('select').forEach((select) => {
        select.addEventListener('change', updateEmailList);
    });
    emailListContainer.addEventListener('click', toggleStar);
</script>
@endsection