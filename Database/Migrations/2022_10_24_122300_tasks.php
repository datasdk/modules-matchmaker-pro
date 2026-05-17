<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class Tasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if(!Schema::hasTable('tasks'))
        Schema::create('tasks', function (Blueprint $table) {

			$table->increments('id');
            $table->bigInteger('case_number')->unsigned();
            $table->string('type',200)->nullable();
			$table->text('name')->nullable();
			$table->string('slug');
			$table->text('resume')->nullable();
			$table->text('description')->nullable();
			$table->string('label',225)->nullable();
            $table->integer('price')->default(0);
			$table->string('link',225)->nullable();
			$table->foreignId('user_id')->nullable();
            $table->string('access',100)->default('public');
            $table->foreignId('company_id')->nullable();
			$table->integer('amount')->default(0);
            $table->bigInteger('potential_matches')->default(0); 
            NestedSet::columns($table);
            $table->integer('search_distance')->nullable()->default(100);
            $table->integer('sorting')->nullable();
            $table->string('status',150)->nullable();
			$table->boolean('active')->default('1');
			$table->text('settings')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('tasks');
    }
}
