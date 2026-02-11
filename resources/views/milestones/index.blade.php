@extends('adminlte::page')

@section('title', 'Hitos')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Hitos del Proyecto</h1>
        <a href="{{ route('milestones.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Añadir Hito
        </a>
    </div>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold">Control de Entregables</h3>
    </div>
    <div class="card-body p-0">
        @if(session('message'))
            <div class="alert alert-success m-3">{{ session('message') }}</div>
        @endif

        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th class="px-4">Título del Hito</th>
                    <th>Costo</th>
                    <th class="text-center">Estado de Pago</th>
                    <th class="text-right px-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($milestones as $milestone)
                <tr>
                    <td class="align-middle px-4">
                        <span class="text-bold">{{ $milestone->title }}</span>
                    </td>
                    <td class="align-middle">
                        <span class="text-muted small">$</span>{{ number_format($milestone->cost, 2) }}
                    </td>
                    <td class="align-middle text-center">
                        {{-- Unificamos a un solo color: badge-primary (azul) --}}
                        <span class="badge badge-primary px-3 py-2" style="font-weight: 500; min-width: 100px;">
                            {{ $milestone->is_paid ? 'PAGADO' : 'PENDIENTE' }}
                        </span>
                    </td>
                    <td class="text-right align-middle px-4">
                        <div class="btn-group">
                            <a href="{{ route('milestones.show', $milestone->id) }}" class="btn btn-sm btn-default" title="Ver Detalles">
                                <i class="fas fa-eye text-info"></i>
                            </a>
                            <a href="{{ route('milestones.edit', $milestone->id) }}" class="btn btn-sm btn-default" title="Editar">
                                <i class="fas fa-edit text-warning"></i>
                            </a>
                            <form action="{{ route('milestones.destroy', $milestone->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default" title="Borrar" onclick="return confirm('¿Eliminar este hito?')">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection