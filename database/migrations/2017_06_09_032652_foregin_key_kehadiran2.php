<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForeginKeyKehadiran2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('kehadiran', function (Blueprint $table) {

        $table->integer('id_datainduk')->unsigned();
        $table->integer('id_jadwal')->unsigned();
        $table->integer('id_hari')->unsigned();
        $table->integer('id_event')->unsigned();

      });

      Schema::table('kehadiran', function (Blueprint $table) {

        $table->foreign('id_datainduk')->references('id')->on('peg_data_induk')
            ->onUpdate('cascade')->onDelete('cascade');

        $table->foreign('id_jadwal')->references('id')->on('jadwal_kerja')
                ->onUpdate('cascade')->onDelete('cascade');

        $table->foreign('id_event')->references('id')->on('event')
                        ->onUpdate('cascade')->onDelete('cascade');

        $table->foreign('id_hari')->references('id')->on('hari_kerja')
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
      Schema::table('kehadiran', function (Blueprint $table) {
          $table->dropForeign(['id_jadwal','id_event','id_hari']);
      });
    }
}
