@extends('adminlte::page')
@section('title', 'Editar Publicación')

@section('content')
<div class="row justify-content-center">
    {{-- Ajustamos a col-12 para que ocupe el ancho de la ventana de contenido --}}
    <div class="col-12">
        <div class="card card-outline shadow" style="border-top: 3px solid #ffc107;">
            <div class="card-header" style="background-color: #ffc107;">
                <h3 class="card-title text-bold text-dark">
                    <i class="fas fa-edit mr-2"></i>Modificar Artículo
                </h3>
            </div>
            
            <form action="{{ route('lab_posts.update', $post->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span class="badge badge-pill px-4 py-2 text-white mb-2" style="background-color: #ffc107;">
                            {{ strtoupper($post->type) }}
                        </span>
                        <h2 class="text-bold text-dark">Editando: {{ $post->title }}</h2>
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="small text-muted text-uppercase">Título del Post</label>
                                <input type="text" name="title" class="form-control form-control-lg" value="{{ $post->title }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="small text-muted text-uppercase">Tipo</label>
                                <select name="type" class="form-control form-control-lg">
                                    <option value="Research" {{ $post->type == 'Research' ? 'selected' : '' }}>Investigación</option>
                                    <option value="Blog" {{ $post->type == 'Blog' ? 'selected' : '' }}>Blog</option>
                                    <option value="Preview" {{ $post->type == 'Preview' ? 'selected' : '' }}>Preview</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label class="small text-muted text-uppercase">Contenido de la Investigación</label>
                        {{-- Ajustamos el alto del textarea para que quepa bien en la ventana --}}
                        <textarea name="body" class="form-control" rows="15" required style="background-color: #fdfdfd; font-size: 1.1rem; line-height: 1.5;">{{ $post->body }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_public" name="is_public" value="1" {{ $post->is_public ? 'checked' : '' }}>
                            <label class="custom-control-label text-muted" for="is_public">Visibilidad pública</label>
                        </div>
                        
                        <div>
                            <a href="{{ route('lab_posts.index') }}" class="btn btn-default border mr-2 px-4 shadow-sm">Cancelar</a>
                            <button type="submit" class="btn btn-warning px-5 text-bold shadow-sm">
                                <i class="fas fa-save mr-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection