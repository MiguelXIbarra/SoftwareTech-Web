<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTitleToNameInMilestonesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasTable('milestones') && Schema::hasColumn('milestones', 'title')) {
            Schema::table('milestones', function (Blueprint $table) {
                $table->renameColumn('title', 'name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasTable('milestones') && Schema::hasColumn('milestones', 'name')) {
            Schema::table('milestones', function (Blueprint $table) {
                $table->renameColumn('name', 'title');
            });
        }
    }
}