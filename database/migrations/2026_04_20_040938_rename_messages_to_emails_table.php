<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('messages') && !Schema::hasTable('emails')) {
            Schema::rename('messages', 'emails');
        }

        Schema::table('emails', function (Blueprint $table) {
            $table->string('subject')->default('Sin Asunto')->after('id');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('emails')) {
            Schema::table('emails', function (Blueprint $table) {
                $table->dropColumn('subject');
            });

            Schema::rename('emails', 'messages');
        }
    }
};
