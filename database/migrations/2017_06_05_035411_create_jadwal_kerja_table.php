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
      Schema::create('hari_kerja', function (Blueprint $table) {
         $table->increments('id');
         $table->enum('hari',['0','1','2','3','4','5','6']);
         $table->string('class',50);
         $table->dateTime('start_date');
         $table->dateTime('end_date');
         $table->timestamps();
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
    }
}
