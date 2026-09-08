<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Project;
use App\Models\Milestone;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Subir un entregable o archivo a un proyecto.
     */
    public function storeProjectAsset(Request $request, $projectId)
    {
        $user = auth()->user();
        $proyecto = Project::findOrFail($projectId);

        // Validar permisos: Superadmin, Admin, Desarrollador asignado o Cliente dueño
        $esAdmin = in_array($user->role, ['superadmin', 'admin']);
        $esDev = ($proyecto->developer_id == $user->id || $proyecto->team()->where('user_id', $user->id)->exists());
        $esCliente = ($proyecto->user_id == $user->id);

        if (!$esAdmin && !$esDev && !$esCliente) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Acceso no autorizado al proyecto.'], 403);
            }
            abort(403);
        }

        $request->validate([
            'archivo' => 'required|file|max:102400', // hasta 100MB
            'nombre'  => 'nullable|string|max:255',
            'tipo'    => 'nullable|string|max:100',
        ]);

        $file = $request->file('archivo');
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());

        // Determinar el tipo de entregable
        $tipo = $request->tipo;
        if (empty($tipo)) {
            if (in_array($extension, ['zip', 'rar', '7z', 'tar', 'gz', 'json', 'sql', 'js', 'html', 'css', 'php'])) {
                $tipo = 'Código / Archivo';
            } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'txt', 'xlsx', 'xls', 'ppt', 'pptx'])) {
                $tipo = 'Documento';
            } elseif (in_array($extension, ['png', 'jpg', 'jpeg', 'svg', 'webp', 'gif', 'fig', 'ai', 'psd'])) {
                $tipo = 'Diseño / Imagen';
            } elseif (in_array($extension, ['apk', 'aab', 'ipa', 'exe', 'dmg', 'bin'])) {
                $tipo = 'Ejecutable / App';
            } elseif (in_array($extension, ['mp4', 'webm', 'mov', 'avi'])) {
                $tipo = 'Video';
            } else {
                $tipo = 'Entregable';
            }
        }

        $nombreFinal = !empty($request->nombre) ? $request->nombre : $originalName;
        if (!empty($extension) && !str_ends_with(strtolower($nombreFinal), '.' . $extension)) {
            $nombreFinal .= '.' . $extension;
        }

        // Guardar archivo en storage/app/public/proyectos/{id}/entregables
        $path = $file->store("proyectos/{$proyecto->id}/entregables", 'public');

        $asset = Asset::create([
            'nombre'         => $nombreFinal,
            'path'           => $path,
            'tipo'           => $tipo,
            'assetable_id'   => $proyecto->id,
            'assetable_type' => Project::class,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Entregable subido exitosamente a la bóveda.',
                'asset'    => [
                    'id'         => $asset->id,
                    'nombre'     => $asset->nombre,
                    'path'       => $asset->path,
                    'tipo'       => $asset->tipo,
                    'url'        => asset('storage/' . $asset->path),
                    'download'   => route('assets.download', $asset->id),
                    'created_at' => $asset->created_at->format('d/m/Y H:i'),
                ]
            ]);
        }

        return back()->with('success', 'Entregable almacenado en la bóveda correctamente.');
    }

    /**
     * Subir comprobante de pago de un hito.
     */
    public function uploadMilestoneReceipt(Request $request, $milestoneId)
    {
        $user = auth()->user();
        $milestone = Milestone::with('project')->findOrFail($milestoneId);
        $proyecto = $milestone->project;

        // Validar permisos
        $esAdmin = in_array($user->role, ['superadmin', 'admin']);
        $esCliente = ($proyecto && $proyecto->user_id == $user->id);

        if (!$esAdmin && !$esCliente) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
            }
            abort(403);
        }

        $request->validate([
            'comprobante' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:20480', // hasta 20MB
        ]);

        $file = $request->file('comprobante');
        $path = $file->store("hitos/{$milestone->id}/comprobantes", 'public');
        $extension = strtolower($file->getClientOriginalExtension());

        // Si el hito ya tenía un comprobante anterior, podemos reemplazarlo o agregar
        $asset = Asset::create([
            'nombre'         => 'Comprobante_' . str_replace(' ', '_', $milestone->name ?? $milestone->title ?? 'Hito') . '.' . $extension,
            'path'           => $path,
            'tipo'           => 'Comprobante de Pago',
            'assetable_id'   => $milestone->id,
            'assetable_type' => Milestone::class,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'     => true,
                'message'     => 'Comprobante de pago subido correctamente.',
                'asset'       => [
                    'id'       => $asset->id,
                    'nombre'   => $asset->nombre,
                    'url'      => asset('storage/' . $asset->path),
                    'download' => route('assets.download', $asset->id),
                ]
            ]);
        }

        return back()->with('success', 'Comprobante de pago subido exitosamente.');
    }

    /**
     * Descargar de forma segura cualquier archivo/asset con su nombre y formato intacto.
     */
    public function download($id)
    {
        $asset = Asset::findOrFail($id);

        if (!Storage::disk('public')->exists($asset->path)) {
            abort(404, 'El archivo solicitado no se encuentra en el almacenamiento.');
        }

        $fullPath = Storage::disk('public')->path($asset->path);

        // Obtener extensión original del archivo
        $extension = pathinfo($asset->path, PATHINFO_EXTENSION);
        if (empty($extension)) {
            $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        }

        $filename = $asset->nombre;
        if (!empty($extension) && !str_ends_with(strtolower($filename), '.' . strtolower($extension))) {
            $filename .= '.' . $extension;
        }

        // Limpiar caracteres no válidos para el nombre de archivo en descarga
        $cleanFilename = str_replace(['"', "'", '/', '\\', ':', '*', '?', '<', '>', '|'], '_', $filename);

        return response()->download($fullPath, $cleanFilename);
    }

    /**
     * Eliminar un entregable o archivo de la bóveda.
     */
    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['superadmin', 'admin'])) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Acceso denegado.'], 403);
            }
            abort(403);
        }

        $asset = Asset::findOrFail($id);

        if (Storage::disk('public')->exists($asset->path)) {
            Storage::disk('public')->delete($asset->path);
        }

        $asset->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Archivo eliminado de la bóveda correctamente.'
            ]);
        }

        return back()->with('success', 'Archivo eliminado de la bóveda.');
    }
}
