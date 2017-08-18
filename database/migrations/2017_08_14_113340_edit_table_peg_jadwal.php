<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTablePegJadwal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peg_jadwal', function (Blueprint $table) {
          $table->integer('ketidakhadiran_id')->unsigned();
          $table->time('in');
          $table->time('out');
          $table->integer('terlambat');
          $table->integer('pulang_awal');
          $table->time('jam_kerja');
        });

        Schema::table('hari_kerja', function (Blueprint $table) {
          $table->time('scan_in1');
          $table->time('scan_in2');
          $table->time('scan_out1');
          $table->time('scan_out2');
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
          $table->dropColumn(['ketidakhadiran_id','in','out','terlambat','pulang_awal','jam_kerja']);
        });

        Schema::table('hari_kerja', function (Blueprint $table) {
          $table->dropColumn(['scan_in1','scan_in2','scan_out1','scan_out2']);
        });
    }
}
