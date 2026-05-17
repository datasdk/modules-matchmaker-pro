<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if(!Schema::hasTable("task_ratings"))
        Schema::create('task_ratings', function (Blueprint $table) {
            
            $table->id();

            $table->string('uid'); 
            $table->string('match_id'); 
            
            // Rater (den der giver rating)
            $table->morphs('rater'); // rater_id + rater_type

            // Target (den der modtager rating)
            $table->morphs('target'); // target_id + target_type

            // Rating
            $table->integer('user_id')->comment('user who rated');

            $table->integer('task_id');

            $table->unsignedTinyInteger('stars')->comment('1-5 stars');
            
            $table->string('type'); 

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
        if(Schema::hasTable("task_ratings"))
        Schema::dropIfExists('task_ratings');
    }
}
