@extends('adminlte::page')

@section('title', 'Detalles del Hito')

@section('content')
<div class="card shadow">
    <div class="card-header bg-info text-white">
        <h3>Hito: {{ $milestone->title }}</h3>
    </div>
    <div class="card-body">
        <p><strong>Proyecto:</strong> {{ $milestone->project->title }}</p>
        <p><strong>Costo:</strong> ${{ number_format($milestone->cost, 2) }}</p>
        <p><strong>Fecha límite:</strong> {{ $milestone->due_date }}</p>
        <p><strong>Estado:</strong> 
            <span class="badge {{ $milestone->is_paid ? 'badge-success' : 'badge-danger' }}">
                {{ $milestone->is_paid ? 'Pagado' : 'Pendiente' }}
            </span>
        </p>
    </div>
    <div class="card-footer">
        <a href="{{ route('milestones.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
@endsection