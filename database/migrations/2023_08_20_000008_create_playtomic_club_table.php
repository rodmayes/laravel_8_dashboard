<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaytomicClubTable extends Migration
{
    public function up()
    {
        Schema::create('playtomic_club', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('playtomic_id', 100);
            $table->integer('days_min_booking')->default(3)->comment('Els díes abans que permeten fer una reserva');
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
        Schema::dropIfExists('playtomic_club');
    }
}
