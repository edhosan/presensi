<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForeginKeyKehadiran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('jadwal_kerja', function (Blueprint $table) {
        $table->string('id_unker', 15);
        $table->string('nama_unker', 100);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwal_kerja', function (Blueprint $table) {
              $table->dropColumn(['id_unker','nama_unker']);
        });
    }
}
