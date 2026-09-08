<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Milestone;
use App\Models\Asset;

class MilestoneDemoSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            // Limpiar datos previos
            Milestone::where('project_id', $project->id)->delete();
            Asset::where('assetable_id', $project->id)->where('assetable_type', Project::class)->delete();

            // 1. Crear Hitos Financieros de Ejemplo
            Milestone::create([
                'project_id' => $project->id,
                'name' => 'Fase 01 - Levantamiento de Arquitectura & Prototipado Base',
                'cost' => 1250.00,
                'is_paid' => true,
                'status' => 'Completado',
                'due_date' => now()->subDays(15),
            ]);

            Milestone::create([
                'project_id' => $project->id,
                'name' => 'Fase 02 - Desarrollo de API, Backend & Módulos Core',
                'cost' => 2400.00,
                'is_paid' => true,
                'status' => 'Completado',
                'due_date' => now()->subDays(3),
            ]);

            Milestone::create([
                'project_id' => $project->id,
                'name' => 'Fase 03 - Integración de Frontend, Testing en Sandbox & Staging',
                'cost' => 1850.00,
                'is_paid' => false,
                'status' => 'En Progreso',
                'due_date' => now()->addDays(10),
            ]);

            Milestone::create([
                'project_id' => $project->id,
                'name' => 'Fase 04 - Despliegue en Producción & Auditoría de Rendimiento',
                'cost' => 1500.00,
                'is_paid' => false,
                'status' => 'Pendiente',
                'due_date' => now()->addDays(24),
            ]);

            // 2. Crear Entregables y Recursos Descargables de Ejemplo
            Asset::create([
                'nombre' => 'Documento_Requerimientos_Tecnicos_v2.1.pdf',
                'path' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                'tipo' => 'documento',
                'assetable_id' => $project->id,
                'assetable_type' => Project::class,
            ]);

            Asset::create([
                'nombre' => 'Manual_Operativo_Arquitectura_Seguridad.pdf',
                'path' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                'tipo' => 'documento',
                'assetable_id' => $project->id,
                'assetable_type' => Project::class,
            ]);

            Asset::create([
                'nombre' => 'Paquete_Compilado_Staging_RC1.zip',
                'path' => 'https://example.com/softwaretech_build.zip',
                'tipo' => 'archivo',
                'assetable_id' => $project->id,
                'assetable_type' => Project::class,
            ]);
        }
    }
}
