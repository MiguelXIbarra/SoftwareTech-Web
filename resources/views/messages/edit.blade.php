@extends('adminlte::page')

@section('title', 'Editar Mensaje')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('messages.update', $message->id) }}" method="post">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Contenido del Mensaje</label>
                <textarea name="content" class="form-control" rows="5" required>{{ $message->content }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning">Guardar Cambios</button>
            <a href="{{ route('messages.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection