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
        if (!Schema::hasTable('absensi')) {
            Schema::create('absensi', function (Blueprint $table) {
            $table->id('absensi_id');
            $table->date('tanggal')->nullable();
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->string('nama_karyawan')->nullable();
            $table->string('posisi')->nullable();
            $table->string('status')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_karyawan')->references('kry_kode')->on('karyawan')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
