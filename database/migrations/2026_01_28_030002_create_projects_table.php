<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('modulo');

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->foreignId('developer_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('estado')->default('');
            $table->integer('progreso')->default(0);
            $table->date('siguiente_entrega')->nullable();
            $table->enum('priority', ['critico', 'alto', 'medio', 'bajo'])->default('medio');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
