<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tasksratingavg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable("task_ratings_avg"))
        Schema::create('task_ratings_avg', function (Blueprint $table) {
            $table->id();  // Primary key ID
            $table->string('subject_type');  // Stores the class name of the subject, e.g. 'User::class'
            $table->foreignId('subject_id');  // Foreign key for subject_id (User model)
            $table->decimal('rating', 3, 1);  // Rating value (rounded to 1 decimal)
            $table->timestamps();  // Created at & Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable("task_ratings_avg"))
        Schema::dropIfExists('task_ratings_avg');
    }
}
