@extends('adminlte::page')
@section('title', 'Nuevo Proyecto')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        {{-- Header Azul Rey Sólido --}}
        <div class="card card-outline shadow" style="border-top: 3px solid #4472f1;">
            <div class="card-header" style="background-color: #4472f1;">
                <h3 class="card-title text-bold text-white">Registrar Nuevo Proyecto</h3>
            </div>
            <form action="{{ route('projects.store') }}" method="post">
                @csrf 
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-project-diagram mr-1 text-muted"></i> Título del Proyecto</label>
                                <input type="text" name="title" class="form-control" placeholder="Ej: Rediseño Web" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-tags mr-1 text-muted"></i> Categoría</label>
                                <select name="category" class="form-control" required>
                                    <option value="Web">Desarrollo Web</option>
                                    <option value="IA">Inteligencia Artificial</option>
                                    <option value="Mobile">App Móvil</option>
                                    <option value="Video Games">Videojuegos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-align-left mr-1 text-muted"></i> Descripción General</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Describe los objetivos del proyecto..." required></textarea>
                    </div>
                </div>
                <div class="card-footer text-center bg-white">
                    <button type="submit" class="btn text-white px-5 text-bold shadow" style="background-color: #4472f1;">
                        <i class="fas fa-check-circle mr-1"></i> Guardar Proyecto
                    </button>
                    <a href="{{ route('projects.index') }}" class="btn btn-default border ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection