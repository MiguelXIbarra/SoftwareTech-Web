<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            if (!Schema::hasColumn('emails', 'subject')) {
                $table->string('subject')->after('id')->default('Sin Asunto');
            }
            if (!Schema::hasColumn('emails', 'is_important')) {
                $table->boolean('is_important')->after('content')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn(['subject', 'is_important']);
        });
        Schema::rename('emails', 'messages');
    }
};
