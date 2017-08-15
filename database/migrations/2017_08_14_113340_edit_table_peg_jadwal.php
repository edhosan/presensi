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
          $table->time('terlambat');
          $table->time('pulang_awal');
          $table->time('jam_kerja');
          $table->enum('status',['H','A','I','C','S','DL','TB']);

          $table->foreign('ketidakhadiran_id')->references('id')->on('ketidakhadiran')
              ->onUpdate('cascade')->onDelete('cascade');
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
          $table->dropColumn(['ketidakhadiran_id','in','out','terlambat','pulang_awal','jam_kerja','status']);
        });
    }
}
