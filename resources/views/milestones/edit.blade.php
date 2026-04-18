@extends('adminlte::page')
@section('title', 'Modificar Hito')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-10">
            <div class="card shadow-lg border-0"
                style="background-color: #0b1120; border-radius: 15px; border-top: 4px solid #ffc107 !important;">

                <div class="card-header border-bottom border-secondary" style="background-color: transparent;">
                    <h3 class="card-title text-bold" style="color: #ffc107;">
                        <i class="fas fa-trophy mr-2"></i>MODIFICAR HITO: {{ strtoupper($milestone->name) }}
                    </h3>
                </div>

                <form action="{{ route('milestones.update', $milestone->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label class="small text-uppercase text-bold"
                                    style="color: #ffc107; letter-spacing: 1px;">Nombre del Hito</label>
                                <input type="text" name="name" class="form-control bg-dark text-white border-secondary"
                                    value="{{ old('name', $milestone->name) }}"
                                    style="border: 1px solid rgba(255, 193, 7, 0.2) !important;" required>
                            </div>
                            <div class="col-md-4">
                                <label class="small text-uppercase text-bold"
                                    style="color: #ffc107; letter-spacing: 1px;">Estado Actual</label>
                                <select name="status" class="form-control bg-dark text-white border-secondary"
                                    style="border-radius: 8px;">
                                    <option value="Pendiente" {{ $milestone->status == 'Pendiente' ? 'selected' : ''
                                        }}>Pendiente</option>
                                    <option value="Completado" {{ $milestone->status == 'Completado' ? 'selected' : ''
                                        }}>Completado</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small text-uppercase text-bold"
                                style="color: #ffc107; letter-spacing: 1px;">Fecha del logro</label>
                            <input type="date" name="due_date" class="form-control bg-dark text-white border-secondary"
                                value="{{ $milestone->due_date ? date('Y-m-d', strtotime($milestone->due_date)) : '' }}"
                                style="border: 1px solid rgba(255, 193, 7, 0.2) !important;">
                        </div>
                    </div>

                    <div class="card-footer border-top border-secondary text-right"
                        style="background-color: transparent; padding: 20px;">
                        <a href="{{ route('milestones.index') }}" class="btn btn-outline-secondary px-4 mr-2"
                            style="border-radius: 20px;">Cancelar</a>
                        <button type="submit" class="btn btn-warning px-5 text-bold shadow"
                            style="background-color: #ffc107; border: none; border-radius: 20px; color: #000;">
                            <i class="fas fa-sync-alt mr-2"></i> ACTUALIZAR HITO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection