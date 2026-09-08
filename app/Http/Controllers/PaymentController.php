<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Payment;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Procesar pago en línea con tarjeta (Crédito / Débito)
     */
    public function payWithCard(Request $request, $milestoneId)
    {
        $milestone = Milestone::with('project')->findOrFail($milestoneId);

        // Verificar que el usuario sea el dueño del proyecto o un admin
        $user = auth()->user();
        if ($user->role === 'cliente' && $milestone->project->user_id !== $user->id) {
            abort(403, 'No tienes permisos sobre este proyecto.');
        }

        if ($milestone->is_paid) {
            return back()->with('info', 'Este hito ya se encuentra liquidado.');
        }

        $request->validate([
            'card_name' => 'required|string|max:255',
            'card_number' => 'required|string|min:15|max:19',
            'exp_month' => 'required|string',
            'exp_year' => 'required|string',
            'cvv' => 'required|string|min:3|max:4',
        ]);

        $transactionId = 'TXN-ST-' . strtoupper(Str::random(12));

        // Registrar el pago en la base de datos
        $payment = Payment::create([
            'milestone_id' => $milestone->id,
            'amount' => $milestone->cost,
            'transaction_id' => $transactionId,
            'payment_method' => 'card_online',
        ]);

        // Marcar el hito como liquidado
        $milestone->update([
            'is_paid' => true,
            'status' => 'Liquidado'
        ]);

        return back()->with('success', "¡Pago de \${$milestone->cost} USD procesado exitosamente! Código de autorización: {$transactionId}");
    }

    /**
     * Subir comprobante de transferencia bancaria
     */
    public function uploadReceipt(Request $request, $milestoneId)
    {
        $milestone = Milestone::with('project')->findOrFail($milestoneId);

        $user = auth()->user();
        if ($user->role === 'cliente' && $milestone->project->user_id !== $user->id) {
            abort(403, 'No tienes permisos sobre este proyecto.');
        }

        $request->validate([
            'receipt' => 'required|file|mimes:pdf,png,jpg,jpeg|max:10240',
        ]);

        $file = $request->file('receipt');
        $fileName = 'comprobante_' . $milestone->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('receipts', $fileName, 'public');

        // Asociar como Asset del proyecto
        Asset::create([
            'project_id' => $milestone->project_id,
            'assetable_id' => $milestone->id,
            'assetable_type' => Milestone::class,
            'name' => 'Comprobante de Pago - ' . ($milestone->name ?? 'Hito'),
            'type' => 'document',
            'url' => Storage::url($path),
            'version' => '1.0'
        ]);

        return back()->with('success', 'Comprobante de transferencia adjuntado correctamente. El equipo de finanzas validará la acreditación a la brevedad.');
    }

    /**
     * Toggle manual de estado de pago por parte del administrador
     */
    public function togglePaymentStatus(Request $request, $milestoneId)
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'Acceso denegado.');
        }

        $milestone = Milestone::findOrFail($milestoneId);
        $newStatus = !$milestone->is_paid;

        $milestone->update([
            'is_paid' => $newStatus,
            'status' => $newStatus ? 'Liquidado' : 'Pendiente'
        ]);

        if ($newStatus && $milestone->payments()->count() === 0) {
            Payment::create([
                'milestone_id' => $milestone->id,
                'amount' => $milestone->cost,
                'transaction_id' => 'MANUAL-SPEI-' . strtoupper(Str::random(8)),
                'payment_method' => 'bank_transfer_manual',
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_paid' => $newStatus,
                'message' => 'Hito actualizado a: ' . ($newStatus ? 'Liquidado' : 'Pendiente')
            ]);
        }

        return back()->with('success', 'Estado del hito actualizado a: ' . ($newStatus ? 'Liquidado' : 'Pendiente'));
    }
}
