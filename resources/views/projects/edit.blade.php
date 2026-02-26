@extends('adminlte::page')
@section('title', 'Editar Proyecto')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline shadow" style="border-top: 3px solid #ffc107;">
            <div class="card-header" style="background-color: #ffc107;">
                <h3 class="card-title text-bold text-dark">Actualizar Datos del Proyecto</h3>
            </div>
            <form action="{{ route('projects.update', $project->id) }}" method="post">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Título</label>
                        <input type="text" name="title" class="form-control" value="{{ $project->title }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Categoría</label>
                                <select name="category" class="form-control">
                                    <option value="Web" {{ $project->category == 'Web' ? 'selected' : '' }}>Web</option>
                                    <option value="IA" {{ $project->category == 'IA' ? 'selected' : '' }}>IA</option>
                                    <option value="Mobile" {{ $project->category == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <select name="status" class="form-control">
                                    <option value="In Progress" {{ $project->status == 'In Progress' ? 'selected' : '' }}>En Progreso</option>
                                    <option value="Completed" {{ $project->status == 'Completed' ? 'selected' : '' }}>Completado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="description" class="form-control" rows="3">{{ $project->description }}</textarea>
                    </div>
                </div>
                <div class="card-footer text-center bg-white">
                    <button type="submit" class="btn btn-warning px-5 text-bold shadow">Actualizar Cambios</button>
                    <a href="{{ route('projects.index') }}" class="btn btn-default border ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection