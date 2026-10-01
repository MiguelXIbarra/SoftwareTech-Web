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
        Schema::table('milestones', function (Blueprint $table) {
            $table->string('approval_status')->default('pending')->after('status'); // pending, in_review, approved, changes_requested
            $table->timestamp('approved_at')->nullable()->after('approval_status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            $table->text('approval_notes')->nullable()->after('approved_by');
            $table->string('approval_ip', 45)->nullable()->after('approval_notes');
            $table->text('feedback_changes')->nullable()->after('approval_ip');
            $table->timestamp('feedback_at')->nullable()->after('feedback_changes');

            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('milestones', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'approval_status',
                'approved_at',
                'approved_by',
                'approval_notes',
                'approval_ip',
                'feedback_changes',
                'feedback_at'
            ]);
        });
    }
};
