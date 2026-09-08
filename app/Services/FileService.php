<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public static function subirYRegistrar($file, $modelo, $nombrePersonalizado = null)
    {
        $mime = $file->getMimeType();
        $tipo = str_contains($mime, 'image') ? 'imagen' : (str_contains($mime, 'video') ? 'video' : 'documento');
        $fileName = time() . '_' . ($nombrePersonalizado ?? $file->getClientOriginalName());

        $supabaseUrl = config('services.supabase.url');
        $serviceKey = config('services.supabase.service_key');

        // Si están configuradas las credenciales de Supabase, intenta subir al Bucket de Supabase Storage
        if (!empty($supabaseUrl) && !empty($serviceKey)) {
            $filePath = 'uploads/' . $fileName;

            try {
                $response = Http::withHeaders([
                    'apikey' => $serviceKey,
                    'Authorization' => 'Bearer ' . $serviceKey,
                ])->attach('file', file_get_contents($file->getRealPath()), $fileName)
                    ->post("{$supabaseUrl}/storage/v1/object/softwaretech-bucket/{$filePath}");

                if ($response->successful()) {
                    $publicUrl = "{$supabaseUrl}/storage/v1/object/public/softwaretech-bucket/{$filePath}";

                    return Asset::create([
                        'nombre' => $nombrePersonalizado ?? $file->getClientOriginalName(),
                        'path' => $publicUrl,
                        'tipo' => $tipo,
                        'assetable_id' => $modelo->id,
                        'assetable_type' => get_class($modelo),
                    ]);
                }
            } catch (\Exception $e) {
                // Si falla la red a Supabase, continúa al almacenamiento local
            }
        }

        // Almacenamiento local (desarrollo local / offline)
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
