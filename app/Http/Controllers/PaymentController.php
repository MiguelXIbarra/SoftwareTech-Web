<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Payment;
use App\Models\Asset;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    /**
     * Procesar pago en línea con tarjeta (Crédito / Débito)
     */
    public function payWithCard(Request $request, $milestoneId)
    {
        $milestone = Milestone::with('project.user')->findOrFail($milestoneId);

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

        // Registrar en bitácora de auditoría
        ActivityLog::log(
            'milestone_paid',
            "Pago en línea de \${$milestone->cost} USD procesado para el hito '{$milestone->name}' por {$user->name}. Folio: {$transactionId}.",
            $milestone->project_id,
            [
                'milestone_id' => $milestone->id,
                'milestone_name' => $milestone->name,
                'amount' => $milestone->cost,
                'transaction_id' => $transactionId,
                'payment_method' => 'card_online'
            ]
        );

        // Notificar por correo electrónico según las preferencias del usuario
        \App\Services\NotificationService::notifyMilestone($milestone, 'paid');

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
        $asset = Asset::create([
            'project_id' => $milestone->project_id,
            'assetable_id' => $milestone->id,
            'assetable_type' => Milestone::class,
            'name' => 'Comprobante de Pago - ' . ($milestone->name ?? 'Hito'),
            'type' => 'document',
            'url' => Storage::url($path),
            'version' => '1.0'
        ]);

        // Registrar en bitácora
        ActivityLog::log(
            'receipt_uploaded',
            "El cliente {$user->name} subió un comprobante de transferencia bancaria para el hito '{$milestone->name}'.",
            $milestone->project_id,
            ['milestone_id' => $milestone->id, 'file_name' => $fileName]
        );

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

        $milestone = Milestone::with('project')->findOrFail($milestoneId);
        $newStatus = !$milestone->is_paid;

        $milestone->update([
            'is_paid' => $newStatus,
            'status' => $newStatus ? 'Liquidado' : 'Pendiente'
        ]);

        if ($newStatus) {
            if ($milestone->payments()->count() === 0) {
                Payment::create([
                    'milestone_id' => $milestone->id,
                    'amount' => $milestone->cost,
                    'transaction_id' => 'MANUAL-SPEI-' . strtoupper(Str::random(8)),
                    'payment_method' => 'bank_transfer_manual',
                ]);
            }

            ActivityLog::log(
                'milestone_paid_manual',
                "El administrador " . auth()->user()->name . " acreditó manualmente el pago del hito '{$milestone->name}'.",
                $milestone->project_id,
                ['milestone_id' => $milestone->id, 'cost' => $milestone->cost]
            );

            \App\Services\NotificationService::notifyMilestone($milestone, 'paid');
        } else {
            ActivityLog::log(
                'milestone_unpaid_manual',
                "El administrador " . auth()->user()->name . " revirtió el estado de pago del hito '{$milestone->name}' a Pendiente.",
                $milestone->project_id,
                ['milestone_id' => $milestone->id]
            );
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

    /**
     * Descarga de Recibo Oficial en PDF
     */
    public function downloadReceiptPdf($milestoneId)
    {
        $milestone = Milestone::with(['project.user', 'payments' => function ($q) {
            $q->latest();
        }])->findOrFail($milestoneId);

        $user = auth()->user();
        if ($user->role === 'cliente' && $milestone->project->user_id !== $user->id) {
            abort(403, 'No tienes permisos para descargar este recibo.');
        }

        if (!$milestone->is_paid) {
            return back()->with('error', 'No se puede generar un recibo para un hito que aún no ha sido liquidado.');
        }

        $payment = $milestone->payments->first();

        $pdf = Pdf::loadView('pdf.recibo_pago', compact('milestone', 'payment'))
            ->setPaper('a4', 'portrait');

        $fileName = 'Recibo_' . Str::slug($milestone->project->nombre) . '_Hito_' . $milestone->id . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Aprobación Formal de Entregable (Sign-Off) por el Cliente
     */
    public function approveMilestone(Request $request, $milestoneId)
    {
        $milestone = Milestone::with('project')->findOrFail($milestoneId);
        $user = auth()->user();

        if ($user->role === 'cliente' && $milestone->project->user_id !== $user->id) {
            abort(403, 'No tienes permisos para aprobar este entregable.');
        }

        $request->validate([
            'approval_notes' => 'nullable|string|max:1000',
        ]);

        $milestone->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $user->id,
            'approval_notes' => $request->input('approval_notes', 'Aprobado conforme por el cliente.'),
            'approval_ip' => $request->ip(),
        ]);

        // Registrar en bitácora de auditoría
        ActivityLog::log(
            'milestone_approved',
            "El cliente {$user->name} otorgó la aprobación formal (Sign-off) para el hito '{$milestone->name}'.",
            $milestone->project_id,
            [
                'milestone_id' => $milestone->id,
                'milestone_name' => $milestone->name,
                'notes' => $request->input('approval_notes'),
                'approved_at' => now()->toIso8601String()
            ]
        );

        return back()->with('success', "¡Has aprobado formalmente el hito '{$milestone->name}'! Se ha registrado el acta de conformidad.");
    }

    /**
     * Solicitud de Ajustes / Cambios por el Cliente
     */
    public function requestMilestoneChanges(Request $request, $milestoneId)
    {
        $milestone = Milestone::with('project')->findOrFail($milestoneId);
        $user = auth()->user();

        if ($user->role === 'cliente' && $milestone->project->user_id !== $user->id) {
            abort(403, 'No tienes permisos sobre este proyecto.');
        }

        $request->validate([
            'feedback_changes' => 'required|string|min:10|max:2000',
        ], [
            'feedback_changes.required' => 'Por favor detalla los ajustes o modificaciones que requieres.',
            'feedback_changes.min' => 'El detalle del ajuste debe contener al menos 10 caracteres.',
        ]);

        $milestone->update([
            'approval_status' => 'changes_requested',
            'feedback_changes' => $request->input('feedback_changes'),
            'feedback_at' => now(),
        ]);

        // Registrar en bitácora de auditoría
        ActivityLog::log(
            'milestone_changes_requested',
            "El cliente {$user->name} solicitó modificaciones en el hito '{$milestone->name}': \"{$request->feedback_changes}\"",
            $milestone->project_id,
            [
                'milestone_id' => $milestone->id,
                'milestone_name' => $milestone->name,
                'feedback' => $request->feedback_changes,
                'requested_at' => now()->toIso8601String()
            ]
        );

        return back()->with('info', "Se han enviado tus observaciones al equipo técnico. Revisaremos los requerimientos de inmediato.");
    }

    /**
     * Cambiar estado de revisión del hito (Admin/Developer)
     */
    public function setReviewStatus(Request $request, $milestoneId)
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin', 'empleado'])) {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'approval_status' => 'required|string|in:pending,in_review,approved,changes_requested',
        ]);

        $milestone = Milestone::with('project')->findOrFail($milestoneId);
        $milestone->update([
            'approval_status' => $request->approval_status,
        ]);

        ActivityLog::log(
            'milestone_review_status_updated',
            "El estado de revisión del hito '{$milestone->name}' fue cambiado a '{$request->approval_status}' por " . auth()->user()->name,
            $milestone->project_id,
            ['milestone_id' => $milestone->id, 'new_status' => $request->approval_status]
        );

        return back()->with('success', 'Estado de revisión actualizado correctamente.');
    }
}
