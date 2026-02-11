@extends('adminlte::page')

@section('title', 'Crear Hito')

@section('content')
<div class="container">
    <div class="row">
        <h2>Añadir Hito de Pago</h2>
    </div>
    <div class="row">
        <form action="{{ route('milestones.store') }}" method="post" class="col-lg-7">
            @csrf
            
            <div class="form-group">
                <label for="project_id">Proyecto Relacionado</label>
                <select class="form-control" name="project_id" required>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="title">Título del Hito</label>
                <input type="text" class="form-control" name="title" placeholder="Ej: Entrega de Prototipo" required>
            </div>

            <div class="form-group">
                <label for="cost">Costo (USD)</label>
                <input type="number" step="0.01" class="form-control" name="cost" required>
            </div>

            <div class="form-group">
                <label for="due_date">Fecha de Entrega</label>
                <input type="date" class="form-control" name="due_date" required>
            </div>

            <button type="submit" class="btn btn-success">Guardar Hito</button>
            <a href="{{ route('milestones.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsectionD