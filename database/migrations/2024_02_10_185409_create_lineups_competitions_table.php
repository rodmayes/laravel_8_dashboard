<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineupsCompetitionsTable extends Migration
{
    public function up()
    {
        Schema::create('lineups_competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('year_id');
            $table->foreign('year_id', 'fk_lineups_year_competition')->references('id')->on('lineups_years');
            $table->integer('couples_number')->nullable()->default(0);

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
        Schema::dropIfExists('lineups_competitions');
    }
}
