<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id', 'poli_id', 'nama', 'spesialisasi', 'no_str', 'alamat', 'no_telepon'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
