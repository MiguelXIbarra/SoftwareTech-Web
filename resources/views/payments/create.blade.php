@extends('adminlte::page')

@section('title', 'Registrar Pago')

@section('content')
<div class="container">
    <div class="row">
        <h2>Registrar Transacción de Pago</h2>
    </div>
    <div class="row">
        <form action="{{ route('payments.store') }}" method="post" class="col-lg-7">
            @csrf
            
            <div class="form-group">
                <label for="milestone_id">Hito Pendiente</label>
                <select class="form-control" name="milestone_id" required>
                    <option value="" disabled selected>Selecciona el hito a pagar</option>
                    @foreach($milestones as $milestone)
                        <option value="{{ $milestone->id }}">{{ $milestone->title }} - ${{ $milestone->cost }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="transaction_id">ID de Transacción (Banco/Stripe/PayPal)</label>
                <input type="text" class="form-control" name="transaction_id" required>
            </div>

            <div class="form-group">
                <label for="amount">Monto Confirmado</label>
                <input type="number" step="0.01" class="form-control" name="amount" required>
            </div>

            <div class="form-group">
                <label for="payment_method">Método de Pago</label>
                <select class="form-control" name="payment_method">
                    <option value="Transferencia">Transferencia Bancaria</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Efectivo">Efectivo / Depósito</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Confirmar Pago</button>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
@endsection