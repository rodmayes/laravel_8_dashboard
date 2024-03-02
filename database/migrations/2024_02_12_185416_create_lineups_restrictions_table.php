<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineupsRestrictionsTable extends Migration
{
    public function up()
    {
        Schema::create('lineups_restrictions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->foreign('player_id', 'fk_lineups_player_restrictions')->references('id')->on('lineups_players');
            $table->unsignedBigInteger('round_id');
            $table->foreign('round_id', 'fk_lineups_couple_restrictions')->references('id')->on('lineups_rounds');
            $table->string('condition', 25)->nullable();
            $table->string('value', 20);

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
        Schema::dropIfExists('lineups_restrictions');
    }
}
