<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentColumnsToMilestonesTable extends Migration
{
    public function up()
    {
        Schema::table('milestones', function (Blueprint $table) {

            if (!Schema::hasColumn('milestones', 'cost')) {
                $table->decimal('cost', 10, 2)->default(0)->after('name');
            }
            
            if (!Schema::hasColumn('milestones', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('cost');
            }
        });
    }

    public function down()
    {
        Schema::table('milestones', function (Blueprint $table) {
            if (Schema::hasColumn('milestones', 'cost')) { $table->dropColumn('cost'); }
            if (Schema::hasColumn('milestones', 'is_paid')) { $table->dropColumn('is_paid'); }
        });
    }
}