<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineupsTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('lineups_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 75);

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
        Schema::dropIfExists('lineups_teams');
    }
}
