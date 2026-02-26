@extends('adminlte::page')
@section('title', 'Nuevo Hito')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-outline shadow" style="border-top: 3px solid #4472f1;">
            <div class="card-header" style="background-color: #4472f1;">
                <h3 class="card-title text-bold text-white">Registrar Nuevo Hito</h3>
            </div>
            <form action="{{ route('milestones.store') }}" method="post">
                @csrf 
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-project-diagram mr-1 text-muted"></i> Proyecto Asociado</label>
                                <select name="project_id" class="form-control" required>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-flag mr-1 text-muted"></i> Nombre del Hito</label>
                                <input type="text" name="name" class="form-control" placeholder="Ej: Entrega de MVP" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-alt mr-1 text-muted"></i> Fecha de Entrega</label>
                                <input type="date" name="due_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center bg-white">
                    <button type="submit" class="btn text-white px-5 text-bold shadow" style="background-color: #4472f1;">
                        Guardar Hito
                    </button>
                    <a href="{{ route('milestones.index') }}" class="btn btn-default border ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection