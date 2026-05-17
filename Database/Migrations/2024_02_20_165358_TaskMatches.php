<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TaskMatches extends Migration {

    public function up()
    {

        
        if(!Schema::hasTable('task_matches'))
        Schema::create('task_matches', function (Blueprint $table) {

            $table->increments('id');
            $table->string('uid');
            $table->integer('task_id');
            $table->integer('match_with_task_id');
            $table->timestamps();

        });

    }

    public function down()
    {
        Schema::dropIfExists('task_matches');
    }
}

