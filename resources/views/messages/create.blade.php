@extends('adminlte::page')

@section('title', 'Enviar Mensaje')

@section('content')
<div class="container">
    <div class="row">
        <h2>Nuevo Mensaje para Proyecto</h2>
    </div>
    <div class="row">
        <form action="{{ route('messages.store') }}" method="post" enctype="multipart/form-data" class="col-lg-7">
            @csrf
            
            <div class="form-group">
                <label for="project_id">Seleccionar Proyecto</label>
                <select class="form-control" name="project_id" required>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="content">Mensaje</label>
                <textarea class="form-control" name="content" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
            </div>

            <div class="form-group">
                <label for="attachment">Archivo Adjunto (Opcional)</label>
                <input type="file" class="form-control-file" name="attachment">
            </div>

            <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
            <a href="{{ route('messages.index') }}" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
@endsection