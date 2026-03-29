<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::all()->groupBy('assetable_type');
        return view('admin.assets.index', compact('assets'));
    }

    public function create()
    {
        return view('admin.assets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'archivo' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,pdf|max:20480',
        ]);

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $path = $file->store('assets', 'public');
            
            // Determinar tipo
            $mime = $file->getMimeType();
            $tipo = str_contains($mime, 'video') ? 'video' : (str_contains($mime, 'image') ? 'imagen' : 'documento');

            Asset::create([
                'nombre' => $request->nombre,
                'path' => $path,
                'tipo' => $tipo,
                'assetable_id' => auth()->id(),
                'assetable_type' => \App\Models\User::class,
            ]);

            return redirect()->route('assets.index')->with('success', 'Archivo procesado en el disco virtual.');
        }
    }

    public function getImage($id)
    {
        $asset = Asset::findOrFail($id);
        return Storage::disk('public')->response($asset->path);
    }

    public function getVideo($id)
    {
        $asset = Asset::findOrFail($id);
        return Storage::disk('public')->response($asset->path);
    }
}