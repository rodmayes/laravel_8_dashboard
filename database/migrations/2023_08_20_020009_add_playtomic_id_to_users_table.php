<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlaytomicIdToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('playtomic_id', 100)->nullable();
            $table->string('playtomic_token', 500)->nullable();
            $table->string('playtomic_refresh_token', 500)->nullable();
            $table->string('playtomic_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('playtomic_id');
        });
    }
}
