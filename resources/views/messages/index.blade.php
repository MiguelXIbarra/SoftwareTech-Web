@extends('adminlte::page')
@section('title', 'Mensajes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-bold">Bandeja de Mensajes</h1>
        <a href="{{ route('messages.create') }}" class="btn text-white shadow-sm" style="background-color: #4472f1;">
            <i class="fas fa-paper-plane mr-1"></i> Enviar Mensaje
        </a>
    </div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        @if($messages->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                <p class="h5 text-muted">No hay mensajes en esta bandeja todavía.</p>
            </div>
        @else
            @foreach($messages as $message)
            <div class="card shadow-sm mb-3 border-left" style="border-left: 5px solid #4472f1 !important;">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label class="text-muted small d-block mb-0">PROYECTO</label>
                            <span class="text-bold text-dark">{{ $message->project->title }}</span>
                        </div>
                        <div class="col-md-5">
                            <label class="text-muted small d-block mb-0">CONTENIDO</label>
                            <span class="text-muted">{{ Str::limit($message->content, 80) }}</span>
                        </div>
                        <div class="col-md-2 text-center">
                            <label class="text-muted small d-block mb-0">FECHA</label>
                            <span class="badge badge-light border px-2 py-1">{{ $message->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="col-md-2 text-right">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('messages.show', $message->id) }}" class="btn btn-sm btn-white border" title="Leer">
                                    <i class="fas fa-envelope-open" style="color: #45a1b5;"></i>
                                </a>
                                @if($message->sender_id === Auth::id())
                                <a href="{{ route('messages.edit', $message->id) }}" class="btn btn-sm btn-white border" title="Editar">
                                    <i class="fas fa-edit" style="color: #ffc107;"></i>
                                </a>
                                <form action="{{ route('messages.destroy', $message->id) }}" method="POST" id="delete-msg-{{ $message->id }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-white border" onclick="confirmDelete({{ $message->id }})">
                                        <i class="fas fa-trash-alt" style="color: #e3342f;"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Borrar mensaje?',
            text: "Esta acción eliminará el mensaje permanentemente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, borrar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-msg-' + id).submit();
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