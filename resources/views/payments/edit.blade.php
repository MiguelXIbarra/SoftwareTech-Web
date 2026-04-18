@extends('adminlte::page')
@section('title', 'Modificar Registro de Pago')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-10">
            <div class="card shadow-lg border-0"
                style="background-color: #0b1120; border-radius: 15px; border-top: 4px solid #ffc107 !important;">

                <div class="card-header border-bottom border-secondary" style="background-color: transparent;">
                    <h3 class="card-title text-bold" style="color: #ffc107;">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>ACTUALIZAR TRANSACCIÓN: #{{ $payment->id }}
                    </h3>
                </div>

                <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="small text-uppercase text-bold"
                                    style="color: #ffc107; letter-spacing: 1px;">Monto ($)</label>
                                <input type="number" step="0.01" name="amount"
                                    class="form-control bg-dark text-white border-secondary"
                                    value="{{ old('amount', $payment->amount) }}"
                                    style="border: 1px solid rgba(255, 193, 7, 0.2) !important;" required>
                            </div>

                            <div class="col-md-6">
                                <label class="small text-uppercase text-bold"
                                    style="color: #ffc107; letter-spacing: 1px;">ID de Transacción</label>
                                <input type="text" name="transaction_id"
                                    class="form-control bg-dark text-white border-secondary"
                                    value="{{ old('transaction_id', $payment->transaction_id) }}"
                                    style="border: 1px solid rgba(255, 193, 7, 0.2) !important;" required>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer border-top border-secondary text-right"
                        style="background-color: transparent; padding: 20px;">
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary px-4 mr-2"
                            style="border-radius: 20px;">Cancelar</a>

                        <button type="submit" class="btn btn-warning px-5 text-bold shadow"
                            style="background-color: #ffc107; border: none; border-radius: 20px; color: #000;">
                            <i class="fas fa-save mr-2"></i> ACTUALIZAR PAGO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection