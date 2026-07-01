<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'clickup_task_id')) {
                $table->renameColumn('clickup_task_id', 'clickup_list_id');
            } else {
                $table->string('clickup_list_id')->nullable()->after('id');
            }
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('clickup_list_id', 'clickup_task_id');
        });
    }
};
