@extends('adminlte::page')

@section('title', 'Mensajes')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Bandeja de Mensajes</h1>
        <a href="{{ route('messages.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-paper-plane"></i> Enviar Nuevo Mensaje
        </a>
    </div>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold">Comunicación de Proyectos</h3>
    </div>
    <div class="card-body p-0">
        @if(session('message'))
            <div class="alert alert-success m-3">{{ session('message') }}</div>
        @endif

        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th class="px-4">Proyecto</th>
                    <th>Contenido</th>
                    <th class="text-center">Enviado</th>
                    <th class="text-right px-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                <tr>
                    <td class="align-middle px-4">
                        <span class="text-bold text-primary">{{ $message->project->title }}</span>
                    </td>
                    <td class="align-middle text-muted">
                        {{ Str::limit($message->content, 60) }}
                    </td>
                    <td class="align-middle text-center">
                        <span class="badge badge-primary px-3 py-2" style="font-weight: 500;">
                            {{ $message->created_at->format('d/m/Y H:i') }}
                        </span>
                    </td>
                    <td class="text-right align-middle px-4">
                        <div class="btn-group">
                            <a href="{{ route('messages.show', $message->id) }}" class="btn btn-sm btn-default" title="Leer Mensaje">
                                <i class="fas fa-search text-info"></i>
                            </a>
                            <a href="{{ route('messages.edit', $message->id) }}" class="btn btn-sm btn-default" title="Editar">
                                <i class="fas fa-edit text-warning"></i>
                            </a>
                            <form action="{{ route('messages.destroy', $message->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default" title="Eliminar" onclick="return confirm('¿Borrar este mensaje?')">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection