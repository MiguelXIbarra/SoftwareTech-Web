@extends('adminlte::page')

@section('title', 'Editar Publicación')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Editar: {{ $post->title }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('lab_posts.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Título</label>
                <input type="text" name="title" class="form-control" value="{{ $post->title }}" required>
            </div>

            <div class="form-group">
                <label>Contenido</label>
                <textarea name="body" class="form-control" rows="10" required>{{ $post->body }}</textarea>
            </div>

            <div class="form-group">
                <label>Tipo</label>
                <select name="type" class="form-control">
                    <option value="Research" {{ $post->type == 'Research' ? 'selected' : '' }}>Investigación</option>
                    <option value="Blog" {{ $post->type == 'Blog' ? 'selected' : '' }}>Blog</option>
                    <option value="Preview" {{ $post->type == 'Preview' ? 'selected' : '' }}>Preview</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning">Actualizar Cambios</button>
            <a href="{{ route('lab_posts.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop