@extends('adminlte::page')
@section('title', 'Detalles del Proyecto')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-dark" style="background-color: #1a222b; border-left: 5px solid #45a1b5;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h1 style="color: #45a1b5;" class="text-bold">{{ $project->name }}</h1>
                            <p style="font-size: 1.1rem; color: #b0b8c1;">{{ $project->description }}</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="badge p-3" style="background-color: #45a1b5; font-size: 1rem;">PROYECTO
                                ACTIVO</span>
                        </div>
                    </div>
                    <hr style="border-top: 1px solid rgba(255,255,255,0.1);">
                    <div class="row text-center mt-4">
                        <div class="col-sm-4">
                            <h5 class="text-muted">Líder de Proyecto</h5>
                            <p class="text-white">{{ $project->user->name ?? 'Sin asignar' }}</p>
                        </div>  
                        <div class="col-sm-4">
                            <h5 class="text-muted">Fecha Inicio</h5>
                            <p class="text-white">{{ $project->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-sm-4">
                            <h5 class="text-muted">ID Sistema</h5>
                            <p class="text-white">#{{ $project->id }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 text-center pb-4">
                    <a href="{{ route('projects.index') }}" class="btn btn-info shadow"
                        style="background-color: #45a1b5; border: none; border-radius: 20px; padding: 10px 40px;">
                        <i class="fas fa-undo mr-2"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection