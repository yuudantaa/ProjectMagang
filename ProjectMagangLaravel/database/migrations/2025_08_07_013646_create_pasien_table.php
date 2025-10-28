<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('Tanggal');
            $table->string('NoKTP', 16);
            $table->string('nama', 50);
            $table->string('TempatLahir', 50);
            $table->date('TanggalLahir');
            $table->string('Agama', 12);
            $table->string('Bahasa', 10);
            $table->string('NoTelp', 16);
            $table->string('Email', 25);
            $table->string('Alamat', 69);
            $table->string('Kecamatan', 30);
            $table->string('Kabupaten', 30);
            $table->string('Provinsi', 30);

            // Define as unsigned bigInteger for foreign key
            $table->unsignedBigInteger('Id_Klinik');
            $table->unsignedBigInteger('Id_Dokter');

            // Foreign key constraints
            $table->foreign('Id_Klinik')->references('id')->on('klinik')->onDelete('cascade');
            $table->foreign('Id_Dokter')->references('id')->on('dokter')->onDelete('cascade');

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
        Schema::dropIfExists('pasien');
    }
}
