<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index()
    {
        $proyectos = [
            [
                'id' => 1,
                'nombre' => 'Core Data Pipeline Engine',
                'modulo' => 'Nexus Core',
                'estado' => 'En Desarrollo',
                'progreso' => 65,
                'siguiente_entrega' => '2026-07-10'
            ]
        ];

        return view('portal.dashboard', compact('proyectos'));
    }

    public function proyecto($proyecto)
{
    $proyectosSimulados = [
        1 => [
            'id' => 1,
            'nombre' => 'Core Data Pipeline Engine',
            'modulo' => 'Nexus Core',
            'estado' => 'En Desarrollo',
            'progreso' => 65,
            'siguiente_entrega' => '2026-07-10',
            'hitos' => [
                [
                    'titulo' => 'Estructura de Base de Datos y Modelo Conceptual',
                    'descripcion' => 'Diseño y migración del esquema relacional en SQL Server.',
                    'estado' => 'Completado',
                    'color' => '#06b6d4'
                ],
                [
                    'titulo' => 'Integración del Núcleo y Lógica de Automatización',
                    'descripcion' => 'Desarrollo del backend service en C# / PHP para el procesamiento del core.',
                    'estado' => 'Completado',
                    'color' => '#06b6d4'
                ],
                [
                    'titulo' => 'Conectividad de APIs y Capa de Servicios Externos',
                    'descripcion' => 'Pruebas de latencia e integraciones con módulos distribuidos en ambiente Staging.',
                    'estado' => 'En Desarrollo',
                    'color' => '#8a2be2'
                ],
                [
                    'titulo' => 'Cifrado de Capas y Auditoría de Seguridad',
                    'descripcion' => 'Implementación de protocolos de resguardo e integridad de datos en tránsito.',
                    'estado' => 'Pendiente',
                    'color' => '#6b7280'
                ],
                [
                    'titulo' => 'Consolidación de Interfaz Gráfica y UX/UI Terminado',
                    'descripcion' => 'Despliegue final del front-end adaptado al entorno oscuro interactivo.',
                    'estado' => 'Pendiente',
                    'color' => '#6b7280'
                ]
            ]
        ]
    ];

    $proyecto = $proyectosSimulados[$proyecto] ?? abort(404);

    return view('portal.proyecto', compact('proyecto'));
}
}
