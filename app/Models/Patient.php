<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'no_rm', 'nik', 'nama', 'tempat_lahir', 'tanggal_lahir', 
        'jenis_kelamin', 'alamat', 'no_telepon', 'nama_kk'
    ];

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
