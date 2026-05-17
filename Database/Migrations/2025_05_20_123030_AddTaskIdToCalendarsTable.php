<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTaskIdToCalendarsTable extends Migration
{
    public function up()
    {
        // Tjek om kolonnen allerede findes
        if (!Schema::hasColumn('calendars', 'task_id')) {
            Schema::table('calendars', function (Blueprint $table) {
                $table->unsignedBigInteger('task_id')->nullable()->index();
            });
        }
    }

    public function down()
    {
        // Tjek om kolonnen findes før vi dropper den
        if (Schema::hasColumn('calendars', 'task_id')) {
            Schema::table('calendars', function (Blueprint $table) {
                $table->dropColumn('task_id');
            });
        }
    }
}