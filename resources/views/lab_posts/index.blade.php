@extends('adminlte::page')

@section('title', 'Laboratorio')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Software Tech Lab</h1>
        <a href="{{ route('lab_posts.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nueva Entrada
        </a>
    </div>
@stop

@section('content')
<div class="row">
    @foreach($posts as $post)
    <div class="col-md-4">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title text-bold">{{ $post->title }}</h3>
            </div>
            <div class="card-body">
                <span class="badge badge-primary mb-2">{{ strtoupper($post->type) }}</span>
                <p class="text-muted small">{{ Str::limit($post->body, 100) }}</p>
            </div>
            <div class="card-footer bg-white border-top-0">
                <div class="d-flex justify-content-between align-items-center">
                    {{-- Botón Leer más original --}}
                    <a href="{{ route('lab_posts.show', $post->id) }}" class="btn btn-xs btn-default text-primary">
                        <i class="fas fa-eye"></i> Leer más
                    </a>
                    
                    {{-- Nuevos botones de acción integrados --}}
                    <div class="btn-group">
                        <a href="{{ route('lab_posts.edit', $post->id) }}" class="btn btn-xs btn-default" title="Editar">
                            <i class="fas fa-edit text-warning"></i>
                        </a>
                        <form action="{{ route('lab_posts.destroy', $post->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-default" title="Eliminar" onclick="return confirm('¿Deseas eliminar esta publicación?')">
                                <i class="fas fa-trash text-danger"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection