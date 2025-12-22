<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id', 'poli_id', 'hari', 'waktu_mulai', 'waktu_selesai', 'kuota_pasien', 'aktif'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }
}
