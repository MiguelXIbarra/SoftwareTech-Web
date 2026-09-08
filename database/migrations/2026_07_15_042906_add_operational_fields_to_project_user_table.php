<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('project_user')) {
            Schema::table('project_user', function (Blueprint $table) {
                if (!Schema::hasColumn('project_user', 'sueldo_proyecto')) {
                    $table->decimal('sueldo_proyecto', 10, 2)->default(0.00)->after('user_id');
                }
                if (!Schema::hasColumn('project_user', 'importancia')) {
                    $table->enum('importancia', ['Baja', 'Media', 'Alta', 'Crítica'])->default('Media')->after('sueldo_proyecto');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('project_user')) {
            Schema::table('project_user', function (Blueprint $table) {
                $columnsToDrop = [];
                if (Schema::hasColumn('project_user', 'sueldo_proyecto')) {
                    $columnsToDrop[] = 'sueldo_proyecto';
                }
                if (Schema::hasColumn('project_user', 'importancia')) {
                    $columnsToDrop[] = 'importancia';
                }
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};
