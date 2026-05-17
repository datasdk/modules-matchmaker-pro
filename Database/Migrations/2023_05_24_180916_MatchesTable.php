<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable('tasks_interactions'))
        Schema::create('tasks_interactions', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id')->index();
            $table->bigInteger('task_id');
            $table->bigInteger('likeable_task_id');
            $table->boolean('should_split')->default(0);
            $table->boolean('should_reduce')->default(0);
            $table->boolean('like')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        if(Schema::hasTable('tasks_interactions'))
        Schema::dropIfExists('tasks_interactions');

    }
}
