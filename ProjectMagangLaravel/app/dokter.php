<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dokter extends Model
{
      protected $table = 'dokter';
        protected $fillable = [
        'nama', 'Spesialisasi', 'NoHP', 'Email','HariPraktek','JamMulai','JamSelesai'
        ];
}
