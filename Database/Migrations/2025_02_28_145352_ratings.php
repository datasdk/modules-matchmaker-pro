<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ratings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable("tasks_ratings_scheduled")){

            Schema::create('tasks_ratings_scheduled', function (Blueprint $table) {
                
                $table->id();
                $table->string('uid')->index(); 
                $table->string('match_id')->index(); 
                $table->foreignId('user_id');
                $table->foreignId('task_id')->nullable();
                $table->foreignId('task_for_rate_id')->nullable();
                $table->softDeletes();
                $table->timestamps();

            });

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        if(Schema::hasTable("tasks_ratings_scheduled")){

            Schema::dropIfExists('tasks_ratings_scheduled');

        }
    }
}
