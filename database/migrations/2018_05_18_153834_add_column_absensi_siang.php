<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAbsensiSiang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hari_kerja', function (Blueprint $table) {
            $table->boolean('is_siang_absensi');
            $table->time('absensi_siang_out_1');
            $table->time('absensi_siang_out_2');
            $table->time('absensi_siang_in_1');
            $table->time('absensi_siang_in_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hari_kerja', function (Blueprint $table) {
            $table->dropColumn(['is_siang_absensi','absensi_siang_out_1','absensi_siang_out_2','absensi_siang_in_1','absensi_siang_in_2']);
        });
    }
}
