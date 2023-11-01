<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTimetableAddColumnPlaytomicIdSummerTable extends Migration
{
    public function up()
    {
        Schema::table('playtomic_timetable', function($table) {
            $table->string('playtomic_id_summer', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playtomic_timetable', function($table) {
            $table->dropColumn('playtomic_id_summer');
        });
    }
}
