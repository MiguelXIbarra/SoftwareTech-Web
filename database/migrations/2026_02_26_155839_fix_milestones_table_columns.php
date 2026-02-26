<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixMilestonesTableColumns extends Migration
{
    public function up()
    {
        Schema::table('milestones', function (Blueprint $table) {

            if (!Schema::hasColumn('milestones', 'status')) {
                $table->string('status')->default('Pendiente')->after('due_date');
            }

            if (Schema::hasColumn('milestones', 'cost')) {
                $table->dropColumn('cost');
            }
            if (Schema::hasColumn('milestones', 'is_paid')) {
                $table->dropColumn('is_paid');
            }

            if (Schema::hasColumn('milestones', 'title')) {
                $table->renameColumn('title', 'name');
            }
        });
    }

    public function down()
    {
        Schema::table('milestones', function (Blueprint $table) {
            if (Schema::hasColumn('milestones', 'status')) { $table->dropColumn('status'); }
            $table->decimal('cost', 10, 2)->nullable();
            $table->boolean('is_paid')->default(false);
        });
    }
}