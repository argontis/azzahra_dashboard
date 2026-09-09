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
        if (!Schema::hasTable('laporan_mingguan')) {
            Schema::create('laporan_mingguan', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->string('nama_karyawan')->nullable();
            $table->string('posisi')->nullable();
            $table->string('periode')->nullable();
            $table->text('target_mingguan')->nullable();
            $table->text('tugas_dilakukan')->nullable();
            $table->text('hasil')->nullable();
            $table->text('kendala')->nullable();
            $table->text('solusi')->nullable();
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
        Schema::dropIfExists('laporan_mingguans');
    }
};
