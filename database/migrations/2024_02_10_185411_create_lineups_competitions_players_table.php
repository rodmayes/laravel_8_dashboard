<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineUpsCompetitionsPlayersTable extends Migration
{
    public function up()
    {
        Schema::create('lineups_competitions_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_team_id');
            $table->foreign('competition_team_id', 'fk_lineups_competition_team_player')->references('id')->on('lineups_competitions_teams');
            $table->unsignedBigInteger('player_id');
            $table->foreign('player_id', 'fk_lineups_player_competition')->references('id')->on('lineups_players');
            $table->integer('ranking')->default(0);

            $table->unique(['competition_team_id', 'player_id'], 'un_lineups_competitions_players');

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
        Schema::dropIfExists('lineups_competitions_players');
    }
}
