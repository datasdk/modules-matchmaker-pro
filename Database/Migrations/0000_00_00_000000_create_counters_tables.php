<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {

        if(!Schema::hasTable('counters'))
        Schema::create("counters", function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->double('initial_value')->default(0);
            $table->double('value')->default(0);
            $table->double('step')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        if(!Schema::hasTable('counterables'))
        Schema::create("counterables", function (Blueprint $table) {
            $table->id();
            $table->morphs('counterable');
            $table->unsignedBigInteger('counter_id');
            $table->double('value')->default(0);
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
        
        if(Schema::hasTable('counters'))
        Schema::dropIfExists("counters");

        if(Schema::hasTable('counterables'))
        Schema::dropIfExists("counterables");
    }

}


