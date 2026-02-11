@extends('adminlte::page')

@section('title', 'Editar Pago')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('payments.update', $payment->id) }}" method="post">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>ID de Transacción</label>
                <input type="text" name="transaction_id" class="form-control" value="{{ $payment->transaction_id }}">
            </div>

            <div class="form-group">
                <label>Monto</label>
                <input type="number" step="0.01" name="amount" class="form-control" value="{{ $payment->amount }}">
            </div>

            <button type="submit" class="btn btn-warning">Actualizar Registro</button>
        </form>
    </div>
</div>
@endsection 