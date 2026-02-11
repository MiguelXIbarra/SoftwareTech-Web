@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>{{ $post->title }}</h2>
            <span class="badge badge-info">{{ $post->type }}</span>
        </div>
        <div class="card-body">
            <p style="white-space: pre-wrap;">{{ $post->body }}</p>
        </div>
        <div class="card-footer text-muted">
            Publicado por: {{ $post->author->name }} | {{ $post->created_at->format('d/m/Y') }}
        </div>
    </div>
</div>
@endsection