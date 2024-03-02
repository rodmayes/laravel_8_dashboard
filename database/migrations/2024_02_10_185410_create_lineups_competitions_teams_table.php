<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineUpsCompetitionsTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('lineups_competitions_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id');
            $table->foreign('competition_id', 'fk_lineups_competition_team')->references('id')->on('lineups_competitions');
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id', 'fk_lineups_team_competition')->references('id')->on('lineups_teams');
            $table->boolean('active')->default(0);

            $table->unique(['competition_id', 'team_id'], 'un_lineups_competitions_teams');

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
        Schema::dropIfExists('lineups_competitions_teams_years');
    }
}
