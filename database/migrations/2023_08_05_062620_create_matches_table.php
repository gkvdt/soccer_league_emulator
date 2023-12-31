<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_matches', function (Blueprint $table) {
            $table->id();
            $table->integer('t1_id')->nullable(false);
            $table->integer('t2_id')->nullable(false);
            $table->integer('weak')->nullable(false);
            $table->integer('league_id')->nullable(false);
            $table->string('result')->nullable(true);
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
        Schema::dropIfExists('matches');
    }
}
