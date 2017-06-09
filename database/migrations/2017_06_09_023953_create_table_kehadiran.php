<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableKehadiran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('kehadiran', function (Blueprint $table) {
         $table->increments('id');
         $table->date('tanggal');
         $table->integer('id_datainduk');
         $table->integer('id_jadwal');
         $table->integer('id_hari');
         $table->integer('id_event');
         $table->time('peg_jam_masuk');
         $table->time('peg_jam_pulang');
         $table->time('terlambat');
         $table->time('pulang_awal');
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
        Schema::drop('kehadiran');
    }
}
