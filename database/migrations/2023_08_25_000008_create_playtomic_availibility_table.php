<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaytomicAvailibilityTable extends Migration
{
    public function up()
    {
        Schema::create('playtomic_availibility', function (Blueprint $table) {
            $table->id();
            $table->string('playtomic_resource_id');
            $table->date('start_date');
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
        Schema::dropIfExists('playtomic_availibility');
    }
}
