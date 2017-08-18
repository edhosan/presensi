<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditColumnPegJadwal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peg_jadwal', function (Blueprint $table) {
          $table->dropColumn(['terlambat','pulang_awal']);
        });

        Schema::table('peg_jadwal', function (Blueprint $table) {
          $table->time('terlambat');
          $table->time('pulang_awal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peg_jadwal', function (Blueprint $table) {
            //
        });
    }
}
