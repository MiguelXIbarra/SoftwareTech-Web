@extends('adminlte::page')
@section('title', 'Lectura de Post')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-dark shadow-lg"
                style="background-color: #1a222b; border-radius: 10px; border: 1px solid #45a1b5;">

                <div class="card-header" style="background-color: #0b1120; border-bottom: 2px solid #45a1b5;">
                    <h3 class="card-title text-bold" style="color: #45a1b5;">
                        <i class="fas fa-book-reader mr-2"></i> {{ strtoupper($post->type) }}
                    </h3>
                </div>

                <div class="card-body" style="background-color: #1a222b; color: #e0e6ed;">
                    <div class="text-center mb-5">
                        <h1 class="display-4 text-bold" style="color: #45a1b5;">{{ $post->title }}</h1>
                        <p class="text-muted">
                            <i class="fas fa-user-edit mr-1"></i> {{ $post->author->name }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-calendar-alt mr-1"></i> {{ $post->created_at->format('d/m/Y') }}
                        </p>
                    </div>

                    <hr style="border-top: 1px solid rgba(69, 161, 181, 0.3);">

                    <div class="p-3" style="font-size: 1.2rem; line-height: 1.8; white-space: pre-wrap;">
                        {{ $post->body }}
                    </div>
                </div>

                <div class="card-footer" style="background-color: #0b1120; border-top: 1px solid #45a1b5;">
                    <div class="text-center">
                        <a href="{{ route('lab_posts.index') }}" class="btn btn-info px-5 shadow-sm"
                            style="background-color: #45a1b5; border: none; border-radius: 20px;">
                            <i class="fas fa-chevron-left mr-2"></i> Volver al Laboratorio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection