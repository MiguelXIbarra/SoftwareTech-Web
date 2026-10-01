<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('clickup')->index();
            $table->string('event_id')->nullable()->index(); // For idempotency checks
            $table->string('event_type')->nullable();
            $table->string('list_id')->nullable()->index();
            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->json('payload')->nullable();
            $table->string('status')->default('pending'); // success, failed, ignored, pending
            $table->text('error_message')->nullable();
            $table->integer('attempts')->default(1);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
