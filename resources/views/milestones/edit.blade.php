@extends('adminlte::page')
@section('title', 'Editar Hito')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-outline shadow" style="border-top: 3px solid #ffc107;">
            <div class="card-header" style="background-color: #ffc107;">
                <h3 class="card-title text-bold text-dark">Modificar Hito</h3>
            </div>
            <form action="{{ route('milestones.update', $milestone->id) }}" method="post">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre del Hito</label>
                                <input type="text" name="name" class="form-control" value="{{ $milestone->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <select name="status" class="form-control">
                                    <option value="Pendiente" {{ $milestone->status == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="Completado" {{ $milestone->status == 'Completado' ? 'selected' : '' }}>Completado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center bg-white">
                    <button type="submit" class="btn btn-warning px-5 text-bold shadow">Actualizar Hito</button>
                    <a href="{{ route('milestones.index') }}" class="btn btn-default border ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection