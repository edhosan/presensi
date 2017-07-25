<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableKetidakhadiranPegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('ketidakhadiran', function (Blueprint $table) {
         $table->increments('id');
         $table->integer('keterangan_id')->unsigned();
         $table->date('start');
         $table->date('end');
         $table->time('jam_start');
         $table->time('jam_end');
         $table->string('keperluan');
         $table->timestamps();
         $table->softDeletes();

         $table->foreign('keterangan_id')->references('id')->on('ref_ijin')
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
      Schema::drop('ketidakhadiran');
    }
}
