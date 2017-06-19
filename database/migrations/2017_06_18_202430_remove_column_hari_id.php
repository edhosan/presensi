<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnHariId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peg_jadwal', function (Blueprint $table) {
              $table->dropForeign(['hari_id']);
              $table->dropColumn(['hari_id']);
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
          $table->integer('hari_id')->unsigned();
          $table->foreign('hari_id')->references('id')->on('hari_kerja')
                  ->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
