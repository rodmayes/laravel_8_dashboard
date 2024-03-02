<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineUpsRoundsTable extends Migration
{
    public function up()
    {
        Schema::create('lineups_rounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id');
            $table->foreign('competition_id', 'fk_lineups_round_competition')->references('id')->on('lineups_competitions');
            $table->dateTime('match_day')->nullable();
            $table->integer('round_number')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lineups_rounds');
    }
}
