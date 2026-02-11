@extends('adminlte::page')

@section('content')
<div class="card shadow">
    <div class="card-header bg-dark">
        <h3 class="card-title">Detalle del Mensaje</h3>
    </div>
    <div class="card-body">
        <p><strong>Enviado por:</strong> Usuario #{{ $message->sender_id }}</p>
        <p><strong>Fecha:</strong> {{ $message->created_at }}</p>
        <hr>
        <p>{{ $message->content }}</p>
    </div>
    <div class="card-footer">
        <a href="{{ route('messages.index') }}" class="btn btn-default">Regresar</a>
    </div>
</div>
@endsection