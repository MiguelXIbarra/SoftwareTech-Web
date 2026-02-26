@extends('adminlte::page')
@section('title', 'Registrar Pago')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-outline shadow" style="border-top: 3px solid #4472f1;">
            <div class="card-header" style="background-color: #4472f1;">
                <h3 class="card-title text-bold text-white">Registrar Transacción de Pago</h3>
            </div>
            <form action="{{ route('payments.store') }}" method="post">
                @csrf 
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-tasks mr-1 text-muted"></i> Hito Pendiente</label>
                                <select class="form-control" name="milestone_id" required>
                                    <option value="" disabled selected>Selecciona el hito a pagar</option>
                                    @foreach($milestones as $milestone)
                                        <option value="{{ $milestone->id }}">{{ $milestone->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-fingerprint mr-1 text-muted"></i> ID de Transacción</label>
                                <input type="text" class="form-control" name="transaction_id" placeholder="Banco / PayPal / Stripe" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-dollar-sign mr-1 text-muted"></i> Monto Confirmado</label>
                                <input type="number" step="0.01" class="form-control" name="amount" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-credit-card mr-1 text-muted"></i> Método de Pago</label>
                                <select class="form-control" name="payment_method">
                                    <option value="Transferencia">Transferencia Bancaria</option>
                                    <option value="PayPal">PayPal</option>
                                    <option value="Efectivo">Efectivo / Depósito</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center bg-white">
                    <button type="submit" class="btn text-white px-5 text-bold shadow" style="background-color: #4472f1;">
                        Confirmar Pago
                    </button>
                    <a href="{{ route('payments.index') }}" class="btn btn-default border ml-2">Volver</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection