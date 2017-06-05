<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJadwalKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('jadwal_kerja', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name',20)->unique();
         $table->string('title',150)->nullable();
         $table->dateTime('start');
         $table->dateTime('end');
         $table->timestamps();
         $table->softDeletes();
      });

      Schema::create('hari_kerja', function (Blueprint $table) {
         $table->increments('id');
         $table->integer('jadwal_id')->unsigned();

         $table->enum('hari',['0','1','2','3','4','5','6']);
         $table->time('jam_masuk');
         $table->time('jam_pulang');
         $table->time('toleransi_terlambat');
         $table->time('toleransi_pulang');
         $table->timestamps();

         $table->foreign('jadwal_id')->references('id')->on('jadwal_kerja')
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
      Schema::drop('hari_kerja');
      Schema::drop('jadwal_kerja');
    }
}
