@extends('adminlte::page')
@section('title', 'Publicar en Lab')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-outline shadow" style="border-top: 3px solid #4472f1;">
            <div class="card-header" style="background-color: #4472f1;">
                <h3 class="card-title text-bold text-white"><i class="fas fa-flask mr-2"></i>Nueva Entrada de Laboratorio</h3>
            </div>
            <form action="{{ route('lab_posts.store') }}" method="post">
                @csrf 
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Título de la Investigación</label>
                                <input type="text" name="title" class="form-control" placeholder="Ej: Avances en IA aplicada" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipo de Contenido</label>
                                <select class="form-control" name="type">
                                    <option value="Research">Investigación</option>
                                    <option value="Blog">Blog</option>
                                    <option value="Preview">Preview</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Cuerpo del Artículo</label>
                        <textarea class="form-control" name="body" rows="10" placeholder="Escribe aquí tu artículo..." required></textarea>
                    </div>

                    <div class="custom-control custom-switch mt-3">
                        <input type="checkbox" class="custom-control-input" id="is_public" name="is_public" value="1" checked>
                        <label class="custom-control-label" for="is_public">Visibilidad pública para clientes</label>
                    </div>
                </div>
                <div class="card-footer text-center bg-white border-top-0">
                    <button type="submit" class="btn text-white px-5 text-bold shadow" style="background-color: #4472f1;">
                        Publicar en el Lab
                    </button>
                    <a href="{{ route('lab_posts.index') }}" class="btn btn-default border ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection