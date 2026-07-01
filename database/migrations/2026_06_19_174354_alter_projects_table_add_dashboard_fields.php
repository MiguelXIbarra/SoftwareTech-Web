<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {

            if (!Schema::hasColumn('projects', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('nombre')->constrained('users')->onDelete('set null');
            }

            if (!Schema::hasColumn('projects', 'developer_id')) {
                $table->foreignId('developer_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->dropForeign(['developer_id']);
            $table->dropColumn('developer_id');
        });
    }
};
