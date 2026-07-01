<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('milestones', function (Blueprint $table) {
            if (!Schema::hasColumn('milestones', 'project_id')) {
                $table->foreignId('project_id')->after('id')->constrained('projects')->onDelete('cascade');
            }
        });
    }
    public function down(): void
    {
        Schema::table('milestones', function (Blueprint $table) {
            if (Schema::hasColumn('milestones', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
        });
    }
};
