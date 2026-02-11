@extends('adminlte::page')

@section('title', 'Crear Proyecto')

@section('content')
<div class="container">
    <div class="row">
        <h2>Crear un Nuevo Proyecto</h2>
    </div>
    <div class="row">
        <form action="{{ route('projects.store') }}" method="post" enctype="multipart/form-data" class="col-lg-7">
            @csrf 
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="title">Título del Proyecto</label>
                <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}" placeholder="Ej: Sistema de Inventario" required>
            </div>

            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{old('description')}}</textarea>
            </div>

            <div class="form-group">
                <label for="category">Categoría</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="" disabled selected>Selecciona una categoría</option>
                    <option value="IA" {{ old('category') == 'IA' ? 'selected' : '' }}>Inteligencia Artificial</option>
                    <option value="Web" {{ old('category') == 'Web' ? 'selected' : '' }}>Desarrollo Web</option>
                    <option value="Mobile" {{ old('category') == 'Mobile' ? 'selected' : '' }}>App Móvil</option>
                    <option value="Video Games" {{ old('category') == 'Video Games' ? 'selected' : '' }}>Videojuegos</option>
                </select>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Guardar Proyecto</button>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection