@extends('adminlte::page')
@section('title', 'Detalle del Pago')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline shadow" style="border-top: 3px solid #45a1b5;">
            <div class="card-header" style="background-color: #45a1b5;">
                <h3 class="card-title text-bold text-white"><i class="fas fa-file-invoice-dollar mr-2"></i>Recibo Digital</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="text-muted small d-block">ID DE TRANSACCIÓN</label>
                        <p class="h5 text-bold">{{ $payment->transaction_id }}</p>
                    </div>
                    <div class="col-6 text-right">
                        <label class="text-muted small d-block">FECHA DE PAGO</label>
                        <p class="h6">{{ $payment->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                
                <div class="bg-light p-4 rounded text-center border">
                    <label class="text-muted small d-block mb-1">MONTO TOTAL CONFIRMADO</label>
                    <h2 class="text-bold text-success">${{ number_format($payment->amount, 2) }}</h2>
                    <span class="badge badge-pill px-4 py-2 text-white" style="background-color: #45a1b5;">
                        {{ strtoupper($payment->payment_method) }}
                    </span>
                </div>
                
                <div class="mt-4">
                    <label class="text-muted small d-block">DETALLE DEL CONCEPTO</label>
                    <p><i class="fas fa-check-circle text-success mr-2"></i>Pago aplicado al hito: <strong>{{ $payment->milestone->name }}</strong></p>
                </div>

                <div class="text-center mt-4 pt-3 border-top">
                    <a href="{{ route('payments.index') }}" class="btn btn-default px-4 border">Regresar al Listado</a>
                    <button onclick="window.print()" class="btn text-white px-4 ml-2 shadow-sm" style="background-color: #45a1b5;">
                        <i class="fas fa-print mr-1"></i> Imprimir Recibo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection