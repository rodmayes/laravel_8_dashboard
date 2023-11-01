<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterClubAddColumnTimetableSummerTable extends Migration
{
    public function up()
    {
        Schema::table('playtomic_club', function($table) {
            $table->boolean('timetable_summer')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playtomic_club', function($table) {
            $table->dropColumn('timetable_summer');
        });
    }
}
