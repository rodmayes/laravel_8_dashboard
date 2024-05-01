<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBookingsAddColumnDurationTable extends Migration
{
    public function up()
    {
        Schema::table('playtomic_booking', function($table) {
            $table->integer('duration')->default(90);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playtomic_booking', function($table) {
            $table->dropColumn('duration');
        });
    }
}
