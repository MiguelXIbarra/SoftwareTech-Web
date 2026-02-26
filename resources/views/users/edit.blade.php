@extends('adminlte::page')

@section('title', 'Editar Usuario')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    .img-container { max-height: 400px; margin-bottom: 10px; }
    .img-container img { max-width: 100%; }
    .cropper-view-box, .cropper-face { border-radius: 50%; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-outline shadow" style="border-top: 3px solid #ffc107;">
            <div class="card-header" style="background-color: #ffc107;">
                <h3 class="card-title text-bold text-dark">Modificar Perfil de Usuario</h3>
            </div>
            
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="post" id="edit-user-form">
                    @csrf 
                    @method('PUT')
                    
                    <div class="form-group text-center">
                        <label class="d-block mb-3 text-muted small text-bold">FOTO DE PERFIL</label>
                        <div class="position-relative d-inline-block">
                            <div style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; border: 4px solid #ffc107; background: #f4f6f9; display: flex; align-items: center; justify-content: center;">
                                {{-- Cargamos la foto actual --}}
                                <img id="preview-image" src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : '' }}" 
                                     style="width: 100%; height: 100%; object-fit: cover; {{ !$user->profile_photo ? 'display:none' : '' }}">
                                @if(!$user->profile_photo) 
                                    <i id="placeholder-icon" class="fas fa-user-circle fa-7x text-muted"></i> 
                                @endif
                            </div>
                            <label for="profile_photo_input" class="btn btn-warning position-absolute shadow" 
                                   style="bottom: 10px; right: 5px; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; cursor: pointer;">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="profile_photo_input" class="d-none" accept="image/*">
                            <input type="hidden" name="profile_photo_base64" id="profile_photo_base64">
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user mr-1 text-muted"></i> Nombre Completo</label>
                                {{-- Usamos el valor actual del usuario --}}
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-envelope mr-1 text-muted"></i> Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user-tag mr-1 text-muted"></i> Rol de Usuario</label>
                                <select name="role" class="form-control" required>
                                    <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Cliente</option>
                                    <option value="developer" {{ $user->role == 'developer' ? 'selected' : '' }}>Desarrollador</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                                    <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Super Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-lock mr-1 text-muted"></i> Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-warning px-5 text-bold shadow">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-default px-4 ml-2 border">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Recorte --}}
<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md shadow-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ffc107;">
                <h5 class="modal-title text-bold text-dark">Ajustar posición</h5>
                <button type="button" class="close text-dark" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <div class="img-container">
                    <img id="image-to-crop" src="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-block text-bold" id="save-crop">Listo</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    // ... (El mismo Script de recorte que ya tenemos funciona perfecto aquí)
    let cropper;
    const photoInput = document.getElementById('profile_photo_input');
    const imageToCrop = document.getElementById('image-to-crop');
    const cropModal = $('#cropModal');

    photoInput.addEventListener('click', function() { this.value = null; });

    photoInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function (event) {
                imageToCrop.src = event.target.result;
                cropModal.modal('show');
            };
            reader.readAsDataURL(files[0]);
        }
    });

    cropModal.on('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 1,
            cropBoxResizable: false
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
    });

    document.getElementById('save-crop').addEventListener('click', function () {
        const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
        const base64Data = canvas.toDataURL('image/jpeg');
        document.getElementById('preview-image').src = base64Data;
        document.getElementById('preview-image').style.display = 'block';
        if(document.getElementById('placeholder-icon')) document.getElementById('placeholder-icon').style.display = 'none';
        document.getElementById('profile_photo_base64').value = base64Data;
        cropModal.modal('hide');
    });
</script>
@endpush