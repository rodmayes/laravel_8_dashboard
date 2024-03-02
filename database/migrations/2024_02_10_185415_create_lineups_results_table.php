<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineupsResultsTable extends Migration
{
    public function up()
    {
        Schema::create('lineups_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('round_id');
            $table->foreign('round_id', 'fk_lineups_couple_round')->references('id')->on('lineups_rounds');
            $table->unsignedBigInteger('player1_id');
            $table->foreign('player1_id', 'fk_lineups_player1_team')->references('id')->on('lineups_players');
            $table->unsignedBigInteger('player2_id');
            $table->foreign('player2_id', 'fk_lineups_player2_team')->references('id')->on('lineups_players');
            $table->unsignedBigInteger('competition_id');
            $table->foreign('competition_id', 'fk_lineups_competition_results')->references('id')->on('lineups_competitions');
            $table->string('score_local_couple', 255)->nullable();
            $table->string('score_visitor_couple', 255)->nullable();
            $table->boolean('winner')->enum('local','visitor');
            $table->unique(['player1_id','player2_id','competition_id','round_id'], 'pk_lineups_results');

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
        Schema::dropIfExists('lineups_results');
    }
}
