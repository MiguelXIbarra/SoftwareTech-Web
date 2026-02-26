@extends('adminlte::page')
@section('title', 'Lectura de Post')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card card-outline shadow" style="border-top: 3px solid #45a1b5;">
            <div class="card-header" style="background-color: #45a1b5;">
                <h3 class="card-title text-bold text-white"><i class="fas fa-book-reader mr-2"></i>Lectura de Artículo</h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-5">
                    <span class="badge badge-pill px-4 py-2 text-white mb-2" style="background-color: #45a1b5;">
                        {{ strtoupper($post->type) }}
                    </span>
                    <h1 class="text-bold text-dark">{{ $post->title }}</h1>
                    <p class="text-muted italic">Publicado por: {{ $post->author->name }} | {{ $post->created_at->format('d/m/Y') }}</p>
                </div>
                
                <div class="p-4 rounded border-left" style="background-color: #fdfdfd; border-left: 5px solid #45a1b5; font-size: 1.15rem; line-height: 1.8; white-space: pre-wrap;">
                    {{ $post->body }}
                </div>
            </div>
            <div class="card-footer bg-white text-center">
                <a href="{{ route('lab_posts.index') }}" class="btn btn-default border px-5 shadow-sm">Volver al Laboratorio</a>
            </div>
        </div>
    </div>
</div>
@endsection