@extends('adminlte::page')
@section('title', 'Editar Mensaje')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline shadow" style="border-top: 3px solid #ffc107;">
            <div class="card-header" style="background-color: #ffc107;">
                <h3 class="card-title text-bold text-dark"><i class="fas fa-edit mr-2"></i>Corregir Mensaje</h3>
            </div>
            <form action="{{ route('messages.update', $message->id) }}" method="post">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label class="text-muted small">CONTENIDO DEL MENSAJE</label>
                        <textarea name="content" class="form-control" rows="8" required>{{ $message->content }}</textarea>
                    </div>
                </div>
                <div class="card-footer text-center bg-white border-top-0">
                    <button type="submit" class="btn btn-warning px-5 text-bold shadow">
                        Guardar Cambios
                    </button>
                    <a href="{{ route('messages.index') }}" class="btn btn-default border ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection