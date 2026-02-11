@extends('adminlte::page')
@section('title', 'Editar Hito')
@section('content')
<div class="card">
    <div class="card-header"><h3>Editar Hito: {{ $milestone->title }}</h3></div>
    <div class="card-body">
        <form action="{{ route('milestones.update', $milestone->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Proyecto</label>
                <select name="project_id" class="form-control">
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ $project->id == $milestone->project_id ? 'selected' : '' }}>
                            {{ $project->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Título</label>
                <input type="text" name="title" class="form-control" value="{{ $milestone->title }}" required>
            </div>
            <div class="form-group">
                <label>Costo</label>
                <input type="number" name="cost" class="form-control" step="0.01" value="{{ $milestone->cost }}" required>
            </div>
            <div class="form-group">
                <label>Fecha de Entrega</label>
                <input type="date" name="due_date" class="form-control" value="{{ $milestone->due_date }}">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Hito</button>
        </form>
    </div>
</div>
@stop