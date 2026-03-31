@extends('adminlte::page')
@section('title', 'Comprobante de Pago')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg"
                style="background-color: #0b1120; border: 1px dashed #45a1b5; border-radius: 0;">
                <div class="card-body text-white">
                    <div class="text-center mb-4">
                        <h4 style="color: #45a1b5;" class="text-bold">SOFTWARE TECH - INVOICE</h4>
                        <small class="text-muted">Transacción ID: #{{ $payment->id }}</small>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Concepto:</span>
                        <span class="text-bold">{{ $payment->description }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span>Fecha Emisión:</span>
                        <span>{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="text-center p-3 rounded" style="background-color: rgba(69, 161, 181, 0.1);">
                        <h2 class="text-bold" style="color: #45a1b5;">${{ number_format($payment->amount, 2) }}</h2>
                        <span class="badge badge-success">PAGO PROCESADO</span>
                    </div>
                </div>
                <div class="card-footer bg-dark text-center">
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-info">Cerrar Detalle</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection