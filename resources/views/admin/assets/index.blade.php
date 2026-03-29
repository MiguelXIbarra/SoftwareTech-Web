@extends('adminlte::page')

@section('title', 'Terminal de Archivos | Software Tech')

@section('content_header')
    <h1 class="m-0 dna-gradient">TERMINAL DE ACTIVOS MULTIMEDIA</h1>
@stop

@section('content')
<div class="container-fluid">
    @if($assets->isEmpty())
        <div class="alert alert-info bg-dark border-primary text-white">
            <i class="fas fa-info-circle"></i> No hay archivos vinculados en el sistema actualmente.
        </div>
    @else
        @foreach($assets as $clase => $archivos)
            <div class="card mb-5 shadow-lg" style="background: rgba(10, 10, 10, 0.8); border: 1px solid rgba(0, 212, 255, 0.3); border-radius: 20px;">
                <div class="card-header border-0">
                    <h3 class="card-title text-white font-weight-bold">
                        <i class="fas fa-folder-open mr-2 text-primary"></i> 
                        SECCIÓN: {{ strtoupper(str_replace('App\\Models\\', '', $clase)) }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($archivos as $archivo)
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="h-100 p-3 text-center rounded shadow-sm" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1);">
                                    @if($archivo->tipo == 'imagen')
                                        <div class="mb-2 rounded overflow-hidden" style="height: 120px;">
                                            <img src="{{ asset('storage/' . $archivo->path) }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                        </div>
                                    @elseif($archivo->tipo == 'video')
                                        <div class="mb-2 d-flex align-items-center justify-content-center bg-black rounded" style="height: 120px;">
                                            <i class="fas fa-video fa-3x text-primary"></i>
                                        </div>
                                    @else
                                        <div class="mb-2 d-flex align-items-center justify-content-center bg-black rounded" style="height: 120px;">
                                            <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                        </div>
                                    @endif
                                    
                                    <p class="text-white small text-truncate mb-1" title="{{ $archivo->nombre }}">
                                        {{ $archivo->nombre }}
                                    </p>
                                    <a href="{{ asset('storage/' . $archivo->path) }}" target="_blank" class="btn btn-xs btn-outline-primary btn-block">
                                        VER ARCHIVO
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@stop

@section('css')
<style>
    .dna-gradient {
        background: linear-gradient(135deg, #00d4ff, #8a2be2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        text-transform: uppercase;
    }
</style>
@stop