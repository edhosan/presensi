<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJumlahHariKetidakhadiran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ketidakhadiran', function (Blueprint $table) {
            $table->integer('jml_hari');
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
             $table->dropColumn(['jml_hari']);
        });
    }
}
