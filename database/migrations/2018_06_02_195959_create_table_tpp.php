<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTpp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tpp_kategori', function (Blueprint $table) {
             $table->increments('id');
             $table->string('nm_kategori');
             $table->timestamps();
          });

        Schema::create('tpp_jenis_pengeluaran', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('tpp_kategori_id')->unsigned();
             $table->string('jns_pengeluaran',150);
             $table->enum('kriteria',['ESELON','GOLONGAN','JABATAN']);             
             $table->timestamps();

             $table->foreign('tpp_kategori_id')->references('id')->on('tpp_kategori')
                 ->onUpdate('cascade')->onDelete('cascade');
          });


        Schema::create('tpp_kriteria', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('tpp_jenis_pengeluaran_id')->unsigned();
             $table->string('kriteria_id',15);
             $table->decimal('lokasi_biasa',10,2);
             $table->decimal('lokasi_terpencil',10,2);
             $table->decimal('lokasi_sangat_terpencil',10,2);
             $table->smallInteger('tahun');
             $table->string('keterangan')->nullable();
             $table->timestamps();

             $table->foreign('tpp_jenis_pengeluaran_id')->references('id')->on('tpp_jenis_pengeluaran')
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
        Schema::drop(['tpp_kategori','tpp_jenis_pengeluaran','tpp_kriteria']);
    }
}
