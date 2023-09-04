<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaytomicTimetableTable extends Migration
{
    public function up()
    {
        Schema::create('playtomic_timetable', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('playtomic_id', 100);
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
        Schema::dropIfExists('playtomic_timetable');
    }
}
