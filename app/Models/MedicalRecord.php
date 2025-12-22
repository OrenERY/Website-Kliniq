<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'patient_id', 'doctor_id', 'queue_id', 'tanggal_kunjungan', 
        'keluhan', 'diagnosis', 'tindakan', 'resep_obat', 'catatan'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
}
