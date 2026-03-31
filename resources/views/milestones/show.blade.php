@extends('adminlte::page')
@section('title', 'Detalle de Hito')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card card-dark shadow-lg"
                style="background-color: #1a222b; border-radius: 10px; overflow: hidden;">
                <div class="card-header" style="background-color: #45a1b5;">
                    <h3 class="card-title text-bold text-white">
                        <i class="fas fa-flag-checkered mr-2"></i> HITO DE INGENIERÍA: #{{ $milestone->id }}
                    </h3>
                </div>
                <div class="card-body" style="color: #e0e6ed;">
                    <div class="row">
                        <div class="col-md-12 mb-4 text-center">
                            <h1 class="display-4 text-bold" style="color: #45a1b5;">{{ $milestone->title }}</h1>
                            <span class="badge badge-pill border border-info text-info px-3 py-2">FASE DE
                                DESARROLLO</span>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="info-box bg-dark border border-secondary">
                                <span class="info-box-icon" style="background-color: rgba(69, 161, 181, 0.2);"><i
                                        class="fas fa-project-diagram text-info"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Proyecto Vinculado</span>
                                    <span class="info-box-number">{{ $milestone->project->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-dark border border-secondary">
                                <span class="info-box-icon" style="background-color: rgba(69, 161, 181, 0.2);"><i
                                        class="fas fa-clock text-info"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Fecha de Registro</span>
                                    <span class="info-box-number">{{ $milestone->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-4 rounded" style="background-color: #0b1120; border: 1px solid #45a1b5;">
                        <h5 class="text-bold mb-3" style="color: #45a1b5;"><i
                                class="fas fa-align-left mr-2"></i>Descripción del Logro:</h5>
                        <p style="font-size: 1.1rem; line-height: 1.7;">{{ $milestone->description }}</p>
                    </div>
                </div>
                <div class="card-footer" style="background-color: #0b1120;">
                    <div class="text-center">
                        <a href="{{ route('milestones.index') }}" class="btn btn-info px-5"
                            style="background-color: #45a1b5; border: none; border-radius: 20px;">
                            <i class="fas fa-list mr-2"></i> Ver todos los hitos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection