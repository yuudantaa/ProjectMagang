<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pasien extends Model
{
   protected $table = 'pasien';
        protected $fillable = [
        'Tanggal', 'NoKTP', 'nama', 'TempatLahir','TanggalLahir','Agama','Bahasa',
        'NoTelp','Email','Alamat','Kecamatan','Kabupaten','Provinsi','Id_Klinik','Id_Dokter'
    ];
}
