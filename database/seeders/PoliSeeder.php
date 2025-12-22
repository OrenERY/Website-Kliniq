<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('poli')->insert([
            ['nama_poli' => 'Umum', 'kode_poli' => 'UM', 'deskripsi' => 'Poli Umum'],
            ['nama_poli' => 'Gigi', 'kode_poli' => 'GG', 'deskripsi' => 'Poli Gigi'],
            ['nama_poli' => 'KIA', 'kode_poli' => 'KIA', 'deskripsi' => 'Kesehatan Ibu dan Anak'],
            ['nama_poli' => 'Lansia', 'kode_poli' => 'LS', 'deskripsi' => 'Poli Lansia'],
        ]);
    }
}