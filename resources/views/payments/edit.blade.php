@extends('adminlte::page')
@section('title', 'Editar Pago')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline shadow" style="border-top: 3px solid #ffc107;">
            <div class="card-header" style="background-color: #ffc107;">
                <h3 class="card-title text-bold text-dark">Modificar Registro de Pago</h3>
            </div>
            <form action="{{ route('payments.update', $payment->id) }}" method="post">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>ID de Transacción</label>
                        <input type="text" name="transaction_id" class="form-control" value="{{ $payment->transaction_id }}">
                    </div>
                    <div class="form-group">
                        <label>Monto</label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ $payment->amount }}">
                    </div>
                </div>
                <div class="card-footer text-center bg-white">
                    <button type="submit" class="btn btn-warning px-5 text-bold shadow">Actualizar Registro</button>
                    <a href="{{ route('payments.index') }}" class="btn btn-default border ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection