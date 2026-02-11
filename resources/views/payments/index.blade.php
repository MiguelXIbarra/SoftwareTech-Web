@extends('adminlte::page')

@section('title', 'Pagos')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Registro de Pagos</h1>
        <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-file-invoice-dollar"></i> Registrar Pago
        </a>
    </div>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold">Historial de Transacciones</h3>
    </div>
    <div class="card-body p-0">
        @if(session('message'))
            <div class="alert alert-success m-3">{{ session('message') }}</div>
        @endif

        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th class="px-4">ID Transacción</th>
                    <th>Monto</th>
                    <th class="text-center">Método</th>
                    <th class="text-right px-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td class="align-middle px-4">
                        <span class="text-muted small">#</span>{{ $payment->transaction_id }}
                    </td>
                    <td class="align-middle text-success">
                        <strong>${{ number_format($payment->amount, 2) }}</strong>
                    </td>
                    <td class="align-middle text-center">
                        {{-- Unificamos a color azul para mantener la línea visual --}}
                        <span class="badge badge-primary px-3 py-2" style="font-weight: 500; min-width: 100px;">
                            {{ strtoupper($payment->payment_method) }}
                        </span>
                    </td>
                    <td class="text-right align-middle px-4">
                        <div class="btn-group">
                            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-default" title="Ver Detalle">
                                <i class="fas fa-search text-info"></i>
                            </a>
                            <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-sm btn-default" title="Editar">
                                <i class="fas fa-edit text-warning"></i>
                            </a>
                            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default" title="Eliminar" onclick="return confirm('¿Deseas eliminar este registro de pago?')">
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