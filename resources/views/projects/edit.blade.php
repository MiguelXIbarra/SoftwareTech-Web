@extends('adminlte::page')

@section('title', 'Editar Proyecto')

@section('content')
<div class="container">
    <div class="row">
        <h2>Actualizar Proyecto: {{ $project->title }}</h2>
    </div>
    <div class="row">
        <form action="{{ route('projects.update', $project->id) }}" method="post" class="col-lg-7">
            @csrf
            @method('PUT')
            
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
                <label for="title">Título</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $project->title }}" required />
            </div>

            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea class="form-control" id="description" name="description" required>{{ $project->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="category">Categoría</label>
                <select name="category" class="form-control">
                    <option value="IA" {{ $project->category == 'IA' ? 'selected' : '' }}>IA</option>
                    <option value="Web" {{ $project->category == 'Web' ? 'selected' : '' }}>Web</option>
                    <option value="Mobile" {{ $project->category == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                    <option value="Video Games" {{ $project->category == 'Video Games' ? 'selected' : '' }}>Video Games</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Estado</label>
                <select name="status" class="form-control">
                    <option value="Lead" {{ $project->status == 'Lead' ? 'selected' : '' }}>Lead</option>
                    <option value="In Progress" {{ $project->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Testing" {{ $project->status == 'Testing' ? 'selected' : '' }}>Testing</option>
                    <option value="Completed" {{ $project->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
            <a href="{{ route('projects.index') }}" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
</div>
@endsection