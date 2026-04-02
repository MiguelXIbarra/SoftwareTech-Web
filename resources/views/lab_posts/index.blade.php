@extends('adminlte::page')
@section('title', 'Laboratorio')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="text-bold text-dark">Software Tech Lab</h1>
    <a href="{{ route('lab_posts.create') }}" class="btn text-white shadow-sm"
        style="background-color: #4472f1; border-radius: 5px;">
        <i class="fas fa-plus"></i>
        <span class="d-none d-sm-inline ml-1">Nueva Entrada</span>
    </a>
</div>
@stop

@section('content')
<div class="row">
    @foreach($posts as $post)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card shadow-sm border-0 card-software-tech">
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

                    <a href="{{ route('lab_posts.show', $post->id) }}"
                        class="btn btn-sm btn-action-outline btn-read-more px-3">
                        <i class="fas fa-eye"></i>
                        <span class="d-none d-md-inline ml-1">Leer más</span>
                    </a>

                    <div class="btn-group">
                        <a href="{{ route('lab_posts.edit', $post->id) }}"
                            class="btn btn-sm btn-action-outline btn-edit-outline mr-1" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('lab_posts.destroy', $post->id) }}" method="POST"
                            id="delete-post-{{ $post->id }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-action-outline btn-delete-outline"
                                onclick="confirmDelete('delete-post-{{ $post->id }}')" title="Eliminar">
                                <i class="fas fa-trash"></i>
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