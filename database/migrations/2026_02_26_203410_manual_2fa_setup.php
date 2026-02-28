<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Si existe manual_2fa_secret, la renombramos
            if (Schema::hasColumn('users', 'manual_2fa_secret')) {
                $table->renameColumn('manual_2fa_secret', 'two_factor_secret');
            } 
            // Si no existe ninguna, la creamos con los campos estándar de Laravel
            elseif (!Schema::hasColumn('users', 'two_factor_secret')) {
                $table->text('two_factor_secret')->nullable()->after('password');
                $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
                $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_secret');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'manual_2fa_secret')) {
                $table->dropColumn('manual_2fa_secret');
            }
        });
    }
};