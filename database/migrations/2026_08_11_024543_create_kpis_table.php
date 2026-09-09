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
        if (!Schema::hasTable('kpi')) {
            Schema::create('kpi', function (Blueprint $table) {
            $table->id('kpi_id');
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->string('nama_karyawan')->nullable();
            $table->string('posisi')->nullable();
            $table->string('status_kerja')->nullable();
            $table->string('siklus')->nullable();
            $table->string('periode')->nullable();
            $table->decimal('kedisiplinan', 5, 2)->default(0);
            $table->decimal('kualitas_kerja', 5, 2)->default(0);
            $table->decimal('produktivitas', 5, 2)->default(0);
            $table->decimal('kerja_tim', 5, 2)->default(0);
            $table->decimal('total', 5, 2)->default(0);
            $table->decimal('rata_rata', 5, 2)->default(0);
            $table->string('kategori')->nullable();
            $table->text('catatan')->nullable();
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
        Schema::dropIfExists('kpis');
    }
};
