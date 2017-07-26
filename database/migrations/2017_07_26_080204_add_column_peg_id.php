<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPegId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ketidakhadiran', function (Blueprint $table) {
          $table->integer('peg_id')->unsigned();

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
        Schema::table('ketidakhadiran', function (Blueprint $table) {
          $table->dropColumn(['peg_id']);
        });
    }
}
