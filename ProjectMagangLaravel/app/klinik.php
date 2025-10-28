<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class klinik extends Model
{
        protected $table = 'klinik';
        protected $fillable = [
        'nama', 'Jenis', 'Gedung', 'Lantai', 'Kapasitas','Keterangan'
        ];
}
