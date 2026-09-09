<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('interviews')) {
            Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kandidat');
            $table->string('posisi');
            $table->dateTime('tanggal_waktu'); // Pastikan baris ini ada
            $table->text('catatan')->nullable();
            $table->string('status')->default('Menunggu');
            $table->timestamps();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
