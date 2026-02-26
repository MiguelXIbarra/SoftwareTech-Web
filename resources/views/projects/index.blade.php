@extends('adminlte::page')
@section('title', 'Proyectos')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="text-bold">Gestión de Proyectos</h1>
    <a href="{{ route('projects.create') }}" class="btn text-white shadow-sm" style="background-color: #4472f1;">
        <i class="fas fa-plus mr-1"></i> Nuevo Proyecto
    </a>
</div>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold">Proyectos Activos</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="bg-light">
                    <tr>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                    <tr>
                        <td class="align-middle text-bold">{{ $project->title }}</td>
                        <td class="align-middle"><span class="badge badge-light border">{{ $project->category }}</span>
                        </td>
                        <td class="align-middle">
                            <span class="badge px-3 py-2 text-white shadow-sm" style="background-color: #4472f1;">
                                {{ $project->status }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('projects.show', $project->id) }}"
                                    class="btn btn-sm btn-white border">
                                    <i class="fas fa-eye" style="color: #45a1b5;"></i>
                                </a>
                                <a href="{{ route('projects.edit', $project->id) }}"
                                    class="btn btn-sm btn-white border">
                                    <i class="fas fa-edit" style="color: #ffc107;"></i>
                                </a>
                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
                                    id="delete-project-{{ $project->id }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-white border"
                                        onclick="confirmDelete({{ $project->id }})">
                                        <i class="fas fa-trash text-danger"></i>
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
                title: '¿Eliminar proyecto?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, borrar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-project-' + id).submit();
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