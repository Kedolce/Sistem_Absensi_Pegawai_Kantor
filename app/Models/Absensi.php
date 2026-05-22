<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'foto_masuk',
        'jam_keluar',
        'foto_keluar',
        'status',
        'location',
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
