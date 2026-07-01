<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();

            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['superadmin', 'admin', 'empleado', 'cliente'])->default('cliente')->after('password');
            }
            if (!Schema::hasColumn('users', 'activation_token')) {
                $table->string('activation_token')->nullable()->unique()->after('role');
            }
            if (!Schema::hasColumn('users', 'active')) {
                $table->boolean('active')->default(false)->after('activation_token');
            }
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
            $table->dropColumn(['role', 'activation_token', 'active']);
        });
    }
};
