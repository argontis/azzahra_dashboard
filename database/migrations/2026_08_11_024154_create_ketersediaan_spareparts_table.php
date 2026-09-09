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
        if (! Schema::hasTable('ketersediaan_sparepart')) {
            Schema::create('ketersediaan_sparepart', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('trans_kode')->nullable();
                $table->string('cos_nama')->nullable();
                $table->string('barang_nama')->nullable();
                $table->string('ketersediaan')->nullable();
                $table->string('status')->default('menunggu');
                $table->timestamps();

                $table->foreign('trans_kode')->references('trans_kode')->on('transaksi')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketersediaan_spareparts');
    }
};
