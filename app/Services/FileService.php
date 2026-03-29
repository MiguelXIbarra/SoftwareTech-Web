<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public static function subirYRegistrar($file, $modelo, $nombrePersonalizado = null)
    {
        $mime = $file->getMimeType();
        
        if (str_contains($mime, 'image')) {
            $tipo = 'imagen';
        } elseif (str_contains($mime, 'video')) {
            $tipo = 'video';
        } else {
            $tipo = 'documento';
        }

        $path = $file->store('uploads', 'public');

        return Asset::create([
            'nombre' => $nombrePersonalizado ?? $file->getClientOriginalName(),
            'path' => $path,
            'tipo' => $tipo,
            'assetable_id' => $modelo->id,
            'assetable_type' => get_class($modelo),
        ]);
    }
}