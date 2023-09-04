<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaytomicSlotsTable extends Migration
{
    public function up()
    {
        Schema::create('playtomic_slots', function (Blueprint $table) {
            $table->id();
            $table->time('start_time');
            $table->integer('duration')->default(90);
            $table->string('price')->default('0 EUR');
            $table->unsignedBigInteger('availibility_id');
            $table->foreign('availibility_id', 'fk_playtomic_slot_availibility')->references('id')->on('playtomic_availibility');
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
        Schema::dropIfExists('playtomic_slots');
    }
}
