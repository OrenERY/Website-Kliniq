<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    protected $fillable = ['nama_poli', 'kode_poli', 'deskripsi'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}
