<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Actualización de Hito - Software Tech</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body, table, td, a, div { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; }
            .content-padding { padding: 24px 18px !important; }
            .header-padding { padding: 28px 18px 20px 18px !important; }
        }
    </style>
</head>
<body bgcolor="#0b0f19" style="margin: 0; padding: 0; background-color: #0b0f19; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #f8fafc;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#0b0f19" style="background-color: #0b0f19; padding: 35px 12px;">
        <tr>
            <td align="center" valign="top">
                <table class="email-container" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #111827; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; overflow: hidden;">
                    
                    <!-- Top Gradient Accent Bar -->
                    <tr>
                        <td height="5" style="background: linear-gradient(90deg, #06b6d4 0%, #3b82f6 50%, #8b5cf6 100%); font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <!-- Header -->
                    <tr>
                        <td class="header-padding" align="center" style="padding: 36px 32px 20px 32px;">
                            <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
                                <tr>
                                    <td style="background-color: rgba(6, 182, 212, 0.12); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 30px; padding: 6px 16px;">
                                        <span style="font-size: 0.72rem; font-weight: 800; letter-spacing: 1.5px; color: #22d3ee; text-transform: uppercase;">
                                            📌 HITOS Y ENTREGABLES
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <h1 style="color: #ffffff; font-size: 1.5rem; font-weight: 800; margin: 0 0 8px 0; line-height: 1.3;">
                                @if($action === 'paid')
                                    ¡Hito Liquidado con Éxito!
                                @elseif($action === 'completed')
                                    ¡Entregable Completado!
                                @elseif($action === 'created')
                                    Nuevo Hito Asignado
                                @else
                                    Actualización de Hito
                                @endif
                            </h1>
                            <p style="color: #94a3b8; font-size: 0.92rem; margin: 0; line-height: 1.5;">
                                Proyecto: <strong style="color: #38bdf8;">{{ $project->nombre }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class="content-padding" style="padding: 28px 32px; background-color: #0f172a; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <p style="color: #e2e8f0; font-size: 1rem; margin: 0 0 16px 0;">
                                Hola <strong style="color: #ffffff;">{{ $user->name }}</strong>,
                            </p>
                            <p style="color: #94a3b8; font-size: 0.92rem; line-height: 1.6; margin: 0 0 24px 0;">
                                @if($action === 'paid')
                                    Te confirmamos que el pago correspondiente al hito <strong>"{{ $milestone->name }}"</strong> ha sido acreditado satisfactoriamente en la plataforma.
                                @elseif($action === 'completed')
                                    El equipo de ingeniería ha marcado como finalizado el hito <strong>"{{ $milestone->name }}"</strong>. Ya se encuentra disponible para su revisión.
                                @elseif($action === 'created')
                                    Se ha planificado y registrado un nuevo hito financiero y técnico dentro de tu proyecto.
                                @else
                                    Hay novedades sobre los avances y compromisos de entrega de tu proyecto.
                                @endif
                            </p>

                            <!-- Milestone Card Details Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; margin-bottom: 26px;">
                                <tr>
                                    <td style="padding: 14px 18px; border-bottom: 1px solid rgba(255,255,255,0.08);">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color: #64748b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Hito / Tarea</td>
                                                <td align="right" style="color: #ffffff; font-size: 0.92rem; font-weight: 700;">{{ $milestone->name }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 18px; border-bottom: 1px solid rgba(255,255,255,0.08);">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color: #64748b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Importe</td>
                                                <td align="right" style="color: #34d399; font-size: 0.95rem; font-weight: 800;">${{ number_format($milestone->cost, 2) }} USD</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 18px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color: #64748b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Estado Actual</td>
                                                <td align="right">
                                                    @if($milestone->is_paid)
                                                        <span style="background: rgba(16,185,129,0.2); color: #34d399; border: 1px solid rgba(16,185,129,0.4); padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">Liquidado</span>
                                                    @else
                                                        <span style="background: rgba(245,158,11,0.2); color: #fbbf24; border: 1px solid rgba(245,158,11,0.4); padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">Pendiente</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $portalUrl }}" style="display: inline-block; background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 10px; font-weight: 700; font-size: 0.92rem; box-shadow: 0 4px 15px rgba(6,182,212,0.3);">
                                            Ver Proyecto en el Portal &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 24px 32px; background-color: #111827;">
                            <p style="color: #64748b; font-size: 0.78rem; margin: 0 0 6px 0;">
                                Has recibido este correo porque tienes activa la opción <strong>Hitos y Entregables</strong> en tu configuración.
                            </p>
                            <p style="color: #475569; font-size: 0.74rem; margin: 0;">
                                Software Tech S.A. &bull; Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
