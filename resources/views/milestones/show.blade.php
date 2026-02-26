@extends('adminlte::page')
@section('title', 'Detalle de Hito')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline shadow" style="border-top: 3px solid #45a1b5;">
            <div class="card-header" style="background-color: #45a1b5;">
                <h3 class="card-title text-bold text-white">Visualizar Hito</h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h2 class="text-bold" style="color: #45a1b5;">{{ $milestone->name }}</h2>
                    <p class="text-muted">Proyecto: {{ $milestone->project->title }}</p>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-md-6">
                        <label class="small text-muted d-block">FECHA LÍMITE</label>
                        <p class="h6"><i class="fas fa-calendar-check mr-2" style="color: #45a1b5;"></i>{{ \Carbon\Carbon::parse($milestone->due_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted d-block">ESTADO</label>
                        <span class="badge px-3 py-2 text-white" style="background-color: #45a1b5;">{{ $milestone->status }}</span>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('milestones.index') }}" class="btn btn-default border">Regresar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection