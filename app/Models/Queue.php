<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'patient_id', 'poli_id', 'doctor_id', 'queue_number', 'queue_date', 
        'status', 'estimated_time', 'priority_level', 'complaint'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }
}
