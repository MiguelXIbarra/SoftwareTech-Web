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
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-sm"
                                        onclick="confirmDelete('delete-project-{{ $project->id }}')"
                                        style="background: rgba(255, 50, 50, 0.05); border: 1px solid rgba(255, 50, 50, 0.3); border-radius: 8px; transition: 0.3s;">
                                        <i class="fas fa-trash"
                                            style="color: #ff3232; filter: drop-shadow(0 0 5px rgba(255, 50, 50, 0.4));"></i>
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