<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksHiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tjekker om tabellen ikke eksisterer, før den oprettes
        if (!Schema::hasTable('tasks_hires')) {
            Schema::create('tasks_hires', function (Blueprint $table) {
                // Definerer kolonnerne
                $table->increments('id');
                $table->integer('task_id');
                $table->integer('hired_task_id');
                $table->timestamps(); // created_at og updated_at
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
        // Sletter tabellen, hvis den eksisterer
        Schema::dropIfExists('tasks_hires');
    }
}
