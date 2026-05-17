<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcquaintancesInteractionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('interactions')) {
            Schema::create('interactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->morphs('subject');
                $table->string('relation')->default('follow')->comment('follow/like/subscribe/favorite/upvote/downvote');
                $table->double('relation_value')->nullable();
                $table->string('relation_type')->nullable();
                $table->timestamps();

                // Korrekt reference til users-tabellen
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('interactions');
    }
}
