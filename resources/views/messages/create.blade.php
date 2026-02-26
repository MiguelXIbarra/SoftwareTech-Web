@extends('adminlte::page')
@section('title', 'Enviar Mensaje')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline shadow" style="border-top: 3px solid #4472f1;">
            <div class="card-header" style="background-color: #4472f1;">
                <h3 class="card-title text-bold text-white"><i class="fas fa-paper-plane mr-2"></i>Nueva Comunicación</h3>
            </div>
            <form action="{{ route('messages.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="card-body">
                    <div class="form-group">
                        <label>Seleccionar Proyecto</label>
                        <select class="form-control" name="project_id" required>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tu Mensaje</label>
                        <textarea class="form-control" name="content" rows="6" placeholder="Escribe aquí los detalles o dudas del proyecto..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-paperclip mr-1"></i> Adjuntar Archivo (Opcional)</label>
                        <input type="file" class="form-control-file" name="attachment">
                    </div>
                </div>
                <div class="card-footer text-center bg-white border-top-0">
                    <button type="submit" class="btn text-white px-5 text-bold shadow" style="background-color: #4472f1;">
                        Enviar Mensaje ahora
                    </button>
                    <a href="{{ route('messages.index') }}" class="btn btn-default border ml-2">Volver</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection