@extends('adminlte::page')
@section('title', 'Detalles del Proyecto')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        {{-- Header Turquesa Sólido --}}
        <div class="card card-outline shadow" style="border-top: 3px solid #45a1b5;">
            <div class="card-header" style="background-color: #45a1b5;">
                <h3 class="card-title text-bold text-white">Visualizar Proyecto</h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h2 class="text-bold" style="color: #45a1b5;">{{ $project->title }}</h2>
                    <span class="badge px-3 py-2 text-white shadow-sm" style="background-color: #45a1b5;">
                        {{ $project->category }}
                    </span>
                </div>

                <hr>

                <div class="row px-4">
                    <div class="col-sm-12 mb-3">
                        <label class="text-muted small d-block">ESTADO ACTUAL</label>
                        <p class="h6"><i class="fas fa-tasks mr-2" style="color: #45a1b5;"></i> {{ $project->status }}</p>
                    </div>
                    <div class="col-sm-12 mb-3">
                        <label class="text-muted small d-block">DESCRIPCIÓN DEL PROYECTO</label>
                        <div class="p-3 bg-light border rounded" style="min-height: 100px;">
                            {{ $project->description }}
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('projects.edit', $project->id) }}" class="btn text-white px-4 shadow-sm" style="background-color: #45a1b5;">
                        <i class="fas fa-edit mr-1"></i> Ir a Editar
                    </a>
                    <a href="{{ route('projects.index') }}" class="btn btn-default px-4 ml-2 border">
                        Regresar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection