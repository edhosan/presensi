<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTableDispensasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Dispensasi', function (Blueprint $table) {
            $table->dropColumn('koreksi_jam_masuk');

            $table->renameColumn('koreksi_jam_pulang','koreksi_jam');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Dispensasi', function (Blueprint $table) {
            $table->time('koreksi_jam_masuk');

            $table->renameColumn('koreksi_jam','koreksi_jam_pulang');
        });
    }
}
