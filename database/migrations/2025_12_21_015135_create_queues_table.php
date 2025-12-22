<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('poli_id')->constrained('poli');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors');
            $table->string('queue_number');
            $table->date('queue_date');
            $table->enum('status', ['menunggu', 'dipanggil', 'dalam_pemeriksaan', 'selesai', 'batal'])->default('menunggu');
            $table->time('estimated_time')->nullable();
            $table->integer('priority_level')->default(0);
            $table->text('complaint');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
