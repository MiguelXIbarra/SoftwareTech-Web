@extends('adminlte::page')

@section('title', 'Detalle del Pago')

@section('content')
<div class="invoice p-3 mb-3 shadow">
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-file-invoice-dollar"></i> Recibo Digital
                <small class="float-right">Fecha: {{ $payment->created_at->format('d/m/Y') }}</small>
            </h4>
        </div>
    </div>
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            <strong>ID de Transacción:</strong><br>
            {{ $payment->transaction_id }}<br>
            <strong>Método:</strong><br>
            {{ $payment->payment_method }}
        </div>
        <div class="col-sm-4 invoice-col">
            <strong>Monto Pagado:</strong><br>
            <h3 class="text-success">${{ number_format($payment->amount, 2) }}</h3>
        </div>
    </div>
    <div class="row no-print mt-4">
        <div class="col-12">
            <a href="{{ route('payments.index') }}" class="btn btn-default">Regresar al Listado</a>
        </div>
    </div>
</div>
@endsection