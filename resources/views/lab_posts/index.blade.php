@extends('adminlte::page')
@section('title', 'Laboratorio')

@push('css')
<style>
    .btn-read-more {
        border-color: #45a1b5;
        color: #45a1b5;
        transition: all 0.3s ease;
    }

    .btn-read-more:hover {
        background-color: #45a1b5 !important;
        color: white !important;
        border-color: #45a1b5;
    }
</style>
@endpush

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-bold">Software Tech Lab</h1>
        <a href="{{ route('lab_posts.create') }}" class="btn text-white shadow-sm" style="background-color: #4472f1;">
            <i class="fas fa-plus mr-1"></i> Nueva Entrada
        </a>
    </div>
@stop

@section('content')
<div class="row">
    @foreach($posts as $post)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0" style="border-top: 4px solid #4472f1 !important;">
            <div class="card-header bg-white border-0 pt-3">
                <h3 class="card-title text-bold text-dark" style="font-size: 1.1rem;">{{ $post->title }}</h3>
            </div>
            <div class="card-body py-2">
                <span class="badge mb-2 text-white shadow-sm" style="background-color: #4472f1;">
                    {{ strtoupper($post->type) }}
                </span>
                <p class="text-muted small">{{ Str::limit($post->body, 120) }}</p>
            </div>
            <div class="card-footer bg-white border-0 pb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('lab_posts.show', $post->id) }}" class="btn btn-sm btn-read-more px-3">
                        <i class="fas fa-eye mr-1"></i> Leer más
                    </a>
                    
                    <div class="btn-group shadow-sm">
                        <a href="{{ route('lab_posts.edit', $post->id) }}" class="btn btn-sm btn-white border">
                            <i class="fas fa-edit" style="color: #ffc107;"></i>
                        </a>
                        <form action="{{ route('lab_posts.destroy', $post->id) }}" method="POST" id="delete-post-{{ $post->id }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-white border" onclick="confirmDelete({{ $post->id }})">
                                <i class="fas fa-trash-alt" style="color: #e3342f;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar entrada?',
            text: "Esta publicación desaparecerá del laboratorio",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-post-' + id).submit();
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