<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_user', function (Blueprint $table) {
            $table->decimal('sueldo_proyecto', 10, 2)->default(0.00)->after('user_id');
            $table->enum('importancia', ['Baja', 'Media', 'Alta', 'Crítica'])->default('Media')->after('sueldo_proyecto');
        });
    }

    public function down(): void
    {
        Schema::table('project_user', function (Blueprint $table) {
            $table->dropColumn(['sueldo_proyecto', 'importancia']);
        });
    }
};
