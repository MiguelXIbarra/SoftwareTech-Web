@extends('adminlte::page')

@section('title', 'Publicar en Lab')

@section('content')
<div class="container">
    <div class="row">
        <h2>Nueva Publicación de Laboratorio</h2>
    </div>
    <div class="row">
        <form action="{{ route('lab_posts.store') }}" method="post" class="col-lg-8">
            @csrf
            <div class="form-group">
                <label for="title">Título de la Investigación o Blog</label>
                <input type="text" class="form-control" name="title" required>
            </div>

            <div class="form-group">
                <label for="type">Tipo de Contenido</label>
                <select class="form-control" name="type">
                    <option value="Research">Investigación</option>
                    <option value="Blog">Blog</option>
                    <option value="Preview">Preview</option>
                </select>
            </div>

            <div class="form-group">
                <label for="body">Contenido</label>
                <textarea class="form-control" name="body" rows="10" placeholder="Escribe aquí tu artículo o código..." required></textarea>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_public" name="is_public" value="1" checked>
                    <label class="custom-control-label" for="is_public">Hacer público para clientes</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Publicar Artículo</button>
            <a href="{{ route('lab_posts.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection