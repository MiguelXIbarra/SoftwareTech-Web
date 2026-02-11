@extends('adminlte::page')

@section('title', 'Detalles del Proyecto')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3>{{ $project->title }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Categoría:</strong> <span class="badge badge-info">{{ $project->category }}</span></p>
            <p><strong>Estado:</strong> 
                <span class="badge {{ $project->status == 'Completed' ? 'badge-success' : 'badge-warning' }}">
                    {{ $project->status }}
                </span>
            </p>
            <hr>
            <h5>Descripción:</h5>
            <p>{{ $project->description }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Regresar al Listado</a>
            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning">Ir a Editar</a>
        </div>
    </div>
</div>
@endsection