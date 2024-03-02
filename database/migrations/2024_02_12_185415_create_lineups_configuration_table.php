<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineupsConfigurationTable extends Migration
{
    public function up()
    {
        Schema::create('lineups_configuration', function (Blueprint $table) {
            $table->id();
            $table->string('name', 75);
            $table->string('value', 255);

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
        Schema::dropIfExists('lineups_configuration');
    }
}
