<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function guardarContacto(Request $request)
    {
        $validated = $request->validate([
            'empresa' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'asunto' => 'nullable|string|max:255',
            'mensaje' => 'required|string|max:5000',
        ]);

        $lead = Lead::create([
            'empresa' => $validated['empresa'] ?? null,
            'email' => $validated['email'],
            'asunto' => $validated['asunto'] ?? null,
            'mensaje' => $validated['mensaje'],
            'ip_address' => $request->ip(),
        ]);

        $numeroWhatsApp = '523328395366';
        $empresa = $lead->empresa ?? 'No especificada';
        $asunto = $lead->asunto ?? 'Consultoría General';
        
        $textoWhatsApp = urlencode(
            "Hola Software Tech, me interesa una consultoría técnica.\n\n" .
            "*Empresa:* {$empresa}\n" .
            "*Correo:* {$lead->email}\n" .
            "*Asunto:* {$asunto}\n" .
            "*Detalles:* {$lead->mensaje}"
        );

        $whatsappUrl = "https://wa.me/{$numeroWhatsApp}?text={$textoWhatsApp}";

        return response()->json([
            'success' => true,
            'message' => '¡Solicitud registrada con éxito! Te estamos redirigiendo a nuestro canal de WhatsApp para darte atención inmediata.',
            'whatsapp_url' => $whatsappUrl,
        ]);
    }
}
