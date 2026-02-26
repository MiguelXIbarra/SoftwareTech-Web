@extends('adminlte::page')
@section('title', 'Pagos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-bold">Registro de Pagos</h1>
        <a href="{{ route('payments.create') }}" class="btn text-white shadow-sm" style="background-color: #4472f1;">
            <i class="fas fa-file-invoice-dollar mr-1"></i> Registrar Pago
        </a>
    </div>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold">Historial de Transacciones</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="bg-light">
                    <tr>
                        <th>ID Transacción</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Fecha</th>
                        <th style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td class="align-middle">
                            <span class="text-muted small">#</span>{{ $payment->transaction_id }}
                        </td>
                        <td class="align-middle text-bold text-success">
                            ${{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="align-middle">
                            <span class="badge px-3 py-2 text-white shadow-sm" style="background-color: #4472f1;">
                                {{ strtoupper($payment->payment_method) }}
                            </span>
                        </td>
                        <td class="align-middle text-muted">
                            {{ $payment->created_at->format('d/m/Y') }}
                        </td>
                        <td class="align-middle">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-white border">
                                    <i class="fas fa-eye" style="color: #45a1b5;"></i>
                                </a>
                                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-sm btn-white border">
                                    <i class="fas fa-edit" style="color: #ffc107;"></i>
                                </a>
                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" id="delete-payment-{{ $payment->id }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-white border" onclick="confirmDelete({{ $payment->id }})">
                                        <i class="fas fa-trash-alt" style="color: #e3342f;"></i>
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
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar registro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-payment-' + id).submit();
            }
        })
    }

    @if(session('message'))
        Swal.fire({
            icon: 'success',
            title: '¡Listo!',
            text: "{{ session('message') }}",
            showConfirmButton: false,
            timer: 1200,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    @endif
</script>
@endpush