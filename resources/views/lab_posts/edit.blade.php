@extends('adminlte::page')
@section('title', 'Modificar Publicación')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-10">
            <div class="card shadow-lg border-0"
                style="background-color: #0b1120; border-radius: 15px; border-top: 4px solid #ffc107 !important;">

                <div class="card-header border-bottom border-secondary" style="background-color: transparent;">
                    <h3 class="card-title text-bold" style="color: #ffc107;">
                        <i class="fas fa-edit mr-2"></i>MODO EDICIÓN: {{ strtoupper($post->type) }}
                    </h3>
                </div>

                <form action="{{ route('lab_posts.update', $post->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-9">
                                <label class="small text-uppercase text-bold"
                                    style="color: #ffc107; letter-spacing: 1px;">Título del Proyecto</label>
                                <input type="text" name="title"
                                    class="form-control form-control-lg bg-dark text-white border-secondary"
                                    value="{{ $post->title }}"
                                    style="border-radius: 8px; border: 1px solid rgba(255, 193, 7, 0.2) !important;"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="small text-uppercase text-bold"
                                    style="color: #ffc107; letter-spacing: 1px;">Categoría</label>
                                <select name="type"
                                    class="form-control form-control-lg bg-dark text-white border-secondary"
                                    style="border-radius: 8px;">
                                    <option value="Research" {{ $post->type == 'Research' ? 'selected' : ''
                                        }}>Investigación</option>
                                    <option value="Blog" {{ $post->type == 'Blog' ? 'selected' : '' }}>Blog</option>
                                    <option value="Preview" {{ $post->type == 'Preview' ? 'selected' : '' }}>Preview
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small text-uppercase text-bold"
                                style="color: #ffc107; letter-spacing: 1px;">Contenido de la Investigación</label>
                            <textarea name="body" class="form-control textarea-autosize" required style="background-color: rgba(255, 193, 7, 0.03); 
                                             color: #e0e6ed; 
                                             border: 1px solid rgba(255, 193, 7, 0.1) !important; /* Borde casi invisible */
                                             font-size: 1.15rem; 
                                             line-height: 1.8; 
                                             padding: 10px 0; /* Padding lateral en 0 para que no parezca una caja */
                                             outline: none;
                                             box-shadow: none;">{{ $post->body }}</textarea>
                        </div>

                        <div class="mt-4 p-3 rounded"
                            style="background-color: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_public" name="is_public"
                                    value="1" {{ $post->is_public ? 'checked' : '' }}>
                                <label class="custom-control-label text-muted" for="is_public"
                                    style="cursor: pointer;">Habilitar acceso público al registro</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer border-top border-secondary text-right"
                        style="background-color: transparent; padding: 20px;">
                        <a href="{{ route('lab_posts.index') }}" class="btn btn-outline-secondary px-4 mr-2"
                            style="border-radius: 20px;">Descartar</a>
                        <button type="submit" class="btn btn-warning px-5 text-bold shadow"
                            style="background-color: #ffc107; border: none; border-radius: 20px; color: #000;">
                            <i class="fas fa-save mr-2"></i> GUARDAR CAMBIOS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection