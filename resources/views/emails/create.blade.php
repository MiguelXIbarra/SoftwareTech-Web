@extends('adminlte::page')
@section('title', 'Redactar Correo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline shadow" style="border-top: 3px solid #4472f1; background-color: #1a222b;">
            <div class="card-header" style="background-color: #4472f1;">
                <h3 class="card-title text-bold text-white"><i class="fas fa-edit mr-2"></i>REDACTAR NUEVO CORREO</h3>
            </div>
            <form action="{{ route('emails.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body" style="color: #e0e6ed;">
                    <div class="form-group">
                        <label class="small text-uppercase text-bold">Asunto del Correo</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" name="subject"
                            placeholder="Ej: Actualización de avance de proyecto" required>
                    </div>
                    <div class="form-group">
                        <label class="small text-uppercase text-bold">Vincular a Proyecto</label>
                        <select class="form-control bg-dark text-white border-secondary" name="project_id" required>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small text-uppercase text-bold">Cuerpo del Mensaje</label>
                        <textarea class="form-control bg-dark text-white border-secondary" name="content" rows="6"
                            placeholder="Escribe el contenido aquí..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="small text-uppercase text-bold"><i class="fas fa-paperclip mr-1"></i> Adjuntar
                            Archivo</label>
                        <input type="file" class="form-control-file" name="attachment">
                    </div>
                </div>
                <div class="card-footer text-center border-top border-secondary" style="background-color: transparent;">
                    <button type="submit" class="btn text-white px-5 text-bold shadow"
                        style="background-color: #4472f1;">
                        <i class="fas fa-paper-plane mr-2"></i> ENVIAR AHORA
                    </button>
                    <a href="{{ route('emails.index') }}" class="btn btn-outline-secondary ml-2">CANCELAR</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection