<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePegJadwal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('peg_jadwal', function (Blueprint $table) {
         $table->increments('id');
         $table->date('tanggal');
         $table->integer('peg_id')->unsigned();
         $table->integer('jadwal_id')->unsigned();
         $table->integer('hari_id')->unsigned();
         $table->timestamps();
         $table->softDeletes();

         $table->foreign('peg_id')->references('id')->on('peg_data_induk')
             ->onUpdate('cascade')->onDelete('cascade');
         $table->foreign('jadwal_id')->references('id')->on('jadwal_kerja')
             ->onUpdate('cascade')->onDelete('cascade');
         $table->foreign('hari_id')->references('id')->on('hari_kerja')
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
        Schema::drop('peg_jadwal');
    }
}
