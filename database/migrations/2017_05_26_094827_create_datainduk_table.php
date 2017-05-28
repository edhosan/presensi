<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataindukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('peg_data_induk', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_finger')->nullable();
          $table->enum('type',['pns','nonpns']);
          $table->string('nip', 20)->nullable();
          $table->string('nama', 100);
          $table->string('gelar_depan',10)->nullable();
          $table->string('gelar_belakang',10)->nullable();
          $table->string('id_unker', 15);
          $table->string('nama_unker', 100);
          $table->string('id_subunit', 15);
          $table->string('nama_subunit', 100);
          $table->string('id_pangkat', 15)->nullable();
          $table->string('golru', 30)->nullable();
          $table->string('pangkat', 100)->nullable();
          $table->string('id_jabatan', 15)->nullable();
          $table->string('nama_jabatan', 100)->nullable();
          $table->string('id_eselon', 15)->nullable();
          $table->date('tmt_pangkat')->nullable();
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
      Schema::drop('peg_data_induk');
    }
}
