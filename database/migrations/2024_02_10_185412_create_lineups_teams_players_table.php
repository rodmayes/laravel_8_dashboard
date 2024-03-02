<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineUpsTeamsPlayersTable extends Migration
{
    public function up()
    {
        Schema::create('lineups_teams_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id', 'fk_lineups_team_player')->references('id')->on('lineups_teams');
            $table->unsignedBigInteger('player_id');
            $table->foreign('player_id', 'fk_lineups_player_team')->references('id')->on('lineups_players');

            $table->unique(['team_id', 'player_id'], 'pk_lineups_teams_players');

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
        Schema::dropIfExists('lineups_teams_players');
    }
}
