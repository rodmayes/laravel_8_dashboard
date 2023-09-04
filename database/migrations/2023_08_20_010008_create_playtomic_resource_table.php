<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaytomicResourceTable extends Migration
{
    public function up()
    {
        Schema::create('playtomic_resource', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('playtomic_id', 100);
            $table->integer('priority')->nullable();
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id', 'fk_playtomic_recource_club')->references('id')->on('playtomic_club');
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
        Schema::dropIfExists('playtomic_resource');
    }
}
