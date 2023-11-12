<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBookingsAddColumnBookedTable extends Migration
{
    public function up()
    {
        Schema::table('playtomic_booking', function($table) {
            $table->datetime('booked_at')->nullable()->default(null);
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
            $table->dropColumn('booked_at');
        });
    }
}
