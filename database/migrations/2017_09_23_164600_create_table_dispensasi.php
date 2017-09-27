<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDispensasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Dispensasi', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('peg_id')->unsigned();
          $table->date('tanggal');
          $table->time('koreksi_jam_masuk');
          $table->time('koreksi_jam_pulang');
          $table->string('alasan');
          $table->string('filename')->nullable();
          $table->timestamps();
          $table->softDeletes();

          $table->foreign('peg_id')->references('id')->on('peg_data_induk')
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
        Schema::table('Dispensasi', function (Blueprint $table) {
          Schema::drop('Dispensasi');
        });
    }
}
