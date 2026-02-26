@extends('adminlte::page')
@section('title', 'Hitos')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="text-bold">Hitos del Proyecto</h1>
    <a href="{{ route('milestones.create') }}" class="btn text-white shadow-sm" style="background-color: #4472f1;">
        <i class="fas fa-flag mr-1"></i> Nuevo Hito
    </a>
</div>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold">Listado de Metas</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="bg-light">
                    <tr>
                        <th>Hito</th>
                        <th>Proyecto</th>
                        <th>Fecha Límite</th>
                        <th>Estado</th>
                        <th style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($milestones as $milestone)
                    <tr>
                        <td class="align-middle text-bold">{{ $milestone->name }}</td>
                        <td class="align-middle text-muted">{{ $milestone->project->title }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($milestone->due_date)->format('d/m/Y') }}</td>
                        <td class="align-middle">
                            <span class="badge px-3 py-2 text-white shadow-sm" style="background-color: #4472f1;">
                                {{ $milestone->status }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('milestones.show', $milestone->id) }}"
                                    class="btn btn-sm btn-white border">
                                    <i class="fas fa-eye" style="color: #45a1b5;"></i>
                                </a>
                                <a href="{{ route('milestones.edit', $milestone->id) }}"
                                    class="btn btn-sm btn-white border">
                                    <i class="fas fa-edit" style="color: #ffc107;"></i>
                                </a>
                                <form action="{{ route('milestones.destroy', $milestone->id) }}" method="POST"
                                    id="delete-form-{{ $milestone->id }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-white border"
                                        onclick="confirmDelete({{ $milestone->id }})">
                                        <i class="fas fa-trash-alt" style="color: #e3342f;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar hito?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

@if(session('message'))
    Swal.fire({
    icon: 'success',
    title: '¡Listo!',
    text: "{{ session('message') }}",
    showConfirmButton: false,
    timer: 1200,
    timerProgressBar: true,
    didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
    });
    @endif
</script>
@endpush