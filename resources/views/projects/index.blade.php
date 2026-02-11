@extends('adminlte::page')
@section('title', 'Proyectos')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Gestión de Proyectos</h1>
        <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nuevo Proyecto
        </a>
    </div>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th class="text-center">Estado</th>
                    <th class="text-right px-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                <tr>
                    <td class="align-middle"><strong>{{ $project->title }}</strong></td>
                    <td class="align-middle">{{ $project->category }}</td>
                    <td class="align-middle text-center">
                        <span class="badge badge-primary px-3 py-2" style="min-width: 80px;">
                            {{ $project->status }}
                        </span>
                    </td>
                    <td class="text-right align-middle px-4">
                        <div class="btn-group">
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-default"><i class="fas fa-eye text-info"></i></a>
                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-default"><i class="fas fa-edit text-warning"></i></a>
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default" onclick="return confirm('¿Eliminar proyecto?')"><i class="fas fa-trash text-danger"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection