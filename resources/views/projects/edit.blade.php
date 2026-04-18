@extends('adminlte::page')
@section('title', 'Modificar Proyecto')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-10">
            <div class="card shadow-lg border-0"
                style="background-color: #0b1120; border-radius: 15px; border-top: 4px solid #ffc107 !important;">

                <div class="card-header border-bottom border-secondary" style="background-color: transparent;">
                    <h3 class="card-title text-bold" style="color: #ffc107;">
                        <i class="fas fa-project-diagram mr-2"></i>ACTUALIZAR PROYECTO: {{ strtoupper($project->title)
                        }}
                    </h3>
                </div>

                <form action="{{ route('projects.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body p-4">
                        <div class="form-group mb-4">
                            <label class="small text-uppercase text-bold"
                                style="color: #ffc107; letter-spacing: 1px;">Título del Proyecto</label>
                            <input type="text" name="title"
                                class="form-control form-control-lg bg-dark text-white border-secondary"
                                value="{{ old('title', $project->title) }}"
                                style="border: 1px solid rgba(255, 193, 7, 0.2) !important;" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="small text-uppercase text-bold"
                                    style="color: #ffc107; letter-spacing: 1px;">Categoría</label>
                                <select name="category" class="form-control bg-dark text-white border-secondary"
                                    style="border-radius: 8px;">
                                    <option value="IA" {{ $project->category == 'IA' ? 'selected' : '' }}>IA</option>
                                    <option value="Web" {{ $project->category == 'Web' ? 'selected' : '' }}>Web</option>
                                    <option value="Mobile" {{ $project->category == 'Mobile' ? 'selected' : '' }}>Mobile
                                    </option>
                                    <option value="Video Games" {{ $project->category == 'Video Games' ? 'selected' : ''
                                        }}>Video Games</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-uppercase text-bold" style="color: #ffc107; letter-spacing: 1px;">Estado del Sistema</label>
                                <select name="status" class="form-control bg-dark text-white border-secondary" style="border-radius: 8px;">
                                    <option value="Lead" {{ $project->status == 'Lead' ? 'selected' : '' }}>En espera</option>
                                    <option value="In Progress" {{ $project->status == 'In Progress' ? 'selected' : '' }}>En progreso</option>
                                    <option value="Testing" {{ $project->status == 'Testing' ? 'selected' : '' }}>En pruebas</option>
                                    <option value="Completed" {{ $project->status == 'Completed' ? 'selected' : '' }}>Completado</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small text-uppercase text-bold"
                                style="color: #ffc107; letter-spacing: 1px;">Descripción Técnica</label>
                            <textarea name="description" class="form-control textarea-autosize"
                                placeholder="Describa el alcance del proyecto..."
                                style="background-color: rgba(255, 193, 7, 0.03); 
                                             color: #e0e6ed; 
                                             border: 1px solid rgba(255, 193, 7, 0.1) !important; 
                                             font-size: 1.1rem; 
                                             line-height: 1.6; 
                                             padding: 10px 0;">{{ old('description', $project->description) }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer border-top border-secondary text-right"
                        style="background-color: transparent; padding: 20px;">
                        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary px-4 mr-2"
                            style="border-radius: 20px;">Cancelar</a>
                        <button type="submit" class="btn btn-warning px-5 text-bold shadow"
                            style="background-color: #ffc107; border: none; border-radius: 20px; color: #000;">
                            <i class="fas fa-sync-alt mr-2"></i> ACTUALIZAR CAMBIOS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 