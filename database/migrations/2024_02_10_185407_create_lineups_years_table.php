<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineupsYearsTable extends Migration
{
    public function up()
    {
        Schema::create('lineups_years', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name', 75);

            $table->primary('id');

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
        Schema::dropIfExists('lineups_years');
    }
}
