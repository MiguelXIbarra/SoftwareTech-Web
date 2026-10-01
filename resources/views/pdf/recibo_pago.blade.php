<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Recibo de Pago - {{ $milestone->name }}</title>
    <style>
        @page {
            margin: 30px 40px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header-table {
            width: 100%;
        }
        .brand-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .brand-subtitle {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .receipt-badge {
            text-align: right;
        }
        .receipt-badge .title {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
        }
        .receipt-badge .folio {
            font-size: 13px;
            color: #334155;
            font-weight: 600;
        }
        .meta-grid {
            width: 100%;
            margin-bottom: 25px;
        }
        .meta-box {
            width: 48%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 16px;
            vertical-align: top;
        }
        .meta-box h4 {
            margin: 0 0 8px 0;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
        }
        .meta-box p {
            margin: 3px 0;
            font-size: 11px;
        }
        .meta-box strong {
            color: #0f172a;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .table-items th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 10px 12px;
            text-align: left;
        }
        .table-items td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .table-items tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .totals-table {
            width: 40%;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .totals-table td {
            padding: 6px 12px;
            font-size: 11px;
        }
        .totals-table .grand-total {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            background-color: #f1f5f9;
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
        }
        .status-stamp {
            display: inline-block;
            padding: 6px 16px;
            border: 2px solid #16a34a;
            color: #16a34a;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            border-radius: 4px;
            letter-spacing: 1px;
        }
        .footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            margin-top: 30px;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
        .security-hash {
            font-family: monospace;
            font-size: 8px;
            color: #64748b;
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 4px;
            margin-top: 8px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    <h1 class="brand-title">SOFTWARE TECHNOLOGIES</h1>
                    <div class="brand-subtitle">Enterprise Software Engineering & Cloud Solutions</div>
                    <div style="font-size: 10px; color: #64748b; margin-top: 4px;">
                        contacto@softwaretech.lat &bull; www.softwaretech.lat
                    </div>
                </td>
                <td class="receipt-badge">
                    <div class="title">RECIBO DE PAGO</div>
                    <div class="folio">FOLIO: #{{ $payment ? ($payment->transaction_id ?? 'REC-'.str_pad($milestone->id, 6, '0', STR_PAD_LEFT)) : 'REC-'.str_pad($milestone->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div style="font-size: 10px; color: #64748b; margin-top: 4px;">
                        Fecha de Emisión: {{ now()->format('d/m/Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="meta-grid">
        <tr>
            <td class="meta-box">
                <h4>DATOS DEL CLIENTE / TITULAR</h4>
                <p><strong>Cliente:</strong> {{ $milestone->project->user->name ?? 'Cliente Corporativo' }}</p>
                <p><strong>Email:</strong> {{ $milestone->project->user->email ?? 'N/A' }}</p>
                <p><strong>Proyecto:</strong> {{ $milestone->project->nombre ?? 'N/A' }}</p>
                <p><strong>Servicio:</strong> {{ $milestone->project->servicio ?? 'Desarrollo de Software' }}</p>
            </td>
            <td style="width: 4%;"></td>
            <td class="meta-box">
                <h4>DETALLES DE LA TRANSACCIÓN</h4>
                <p><strong>Estado:</strong> <span style="color: #16a34a; font-weight: bold;">LIQUIDADO / ACREDITADO</span></p>
                <p><strong>Método de Pago:</strong> {{ $payment && $payment->payment_method === 'card_online' ? 'Tarjeta de Crédito / Débito (En línea)' : 'Transferencia Electrónica SPEI / Bancaria' }}</p>
                <p><strong>ID Transacción / Ref:</strong> {{ $payment->transaction_id ?? 'REF-MANUAL-'.$milestone->id }}</p>
                <p><strong>Fecha de Liquidación:</strong> {{ ($payment->created_at ?? $milestone->updated_at)->format('d/m/Y H:i') }}</p>
            </td>
        </tr>
    </table>

    <table class="table-items">
        <thead>
            <tr>
                <th style="width: 10%;">Item</th>
                <th style="width: 55%;">Descripción del Entregable / Hito</th>
                <th style="width: 15%; text-align: center;">Estado Sign-Off</th>
                <th style="width: 20%; text-align: right;">Monto (USD)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: bold;">01</td>
                <td>
                    <strong>{{ $milestone->name }}</strong>
                    <div style="font-size: 10px; color: #64748b; margin-top: 2px;">
                        Fase correspondiente al proyecto &ldquo;{{ $milestone->project->nombre }}&rdquo;.
                        @if($milestone->approval_status === 'approved')
                            <br><span style="color: #059669;">&check; Entregable formalmente validado y aprobado por el cliente el {{ $milestone->approved_at?->format('d/m/Y') }}.</span>
                        @endif
                    </div>
                </td>
                <td style="text-align: center;">
                    @if($milestone->approval_status === 'approved')
                        <span style="color: #059669; font-weight: bold;">Aprobado</span>
                    @else
                        <span style="color: #64748b;">Completado</span>
                    @endif
                </td>
                <td style="text-align: right; font-weight: bold;">
                    ${{ number_format($milestone->cost, 2) }} USD
                </td>
            </tr>
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td style="text-align: right; color: #64748b;">Subtotal:</td>
            <td style="text-align: right; font-weight: 600;">${{ number_format($milestone->cost, 2) }} USD</td>
        </tr>
        <tr>
            <td style="text-align: right; color: #64748b;">Impuestos / Comisiones:</td>
            <td style="text-align: right; font-weight: 600;">$0.00 USD</td>
        </tr>
        <tr class="grand-total">
            <td style="text-align: right;">TOTAL PAGADO:</td>
            <td style="text-align: right;">${{ number_format($milestone->cost, 2) }} USD</td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 15px;">
        <tr>
            <td style="width: 60%;">
                <div class="status-stamp">
                    &check; PAGADO Y CONCILIADO
                </div>
                <div style="margin-top: 12px; font-size: 10px; color: #64748b;">
                    Este documento constituye un comprobante formal de recepción de fondos y liquidación del hito mencionado.
                </div>
            </td>
            <td style="width: 40%; text-align: right;">
                <div style="font-size: 9px; color: #64748b; text-transform: uppercase;">Cadena de Validación Digital</div>
                <div class="security-hash">
                    SHA256:{{ hash('sha256', $milestone->id . '-' . $milestone->cost . '-' . ($payment->transaction_id ?? 'MANUAL') . '-softwaretech') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Software Technologies &copy; {{ date('Y') }} &bull; Documento generado electrónicamente a través del portal de clientes seguro.
    </div>

</body>
</html>
