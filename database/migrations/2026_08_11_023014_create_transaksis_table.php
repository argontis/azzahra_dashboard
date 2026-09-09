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
        if (! Schema::hasTable('transaksi')) {
            Schema::create('transaksi', function (Blueprint $table) {
                $table->id('trans_kode');
                $table->unsignedBigInteger('cos_kode'); // costomer foreign key
                $table->unsignedBigInteger('kry_kode')->nullable(); // karyawan foreign key
                $table->string('trans_status')->default('Baru');
                $table->date('cos_tanggal')->nullable();
                $table->time('cos_jam')->nullable();
                $table->decimal('trans_discount', 15, 2)->default(0);
                $table->decimal('trans_total', 15, 2)->default(0);
                $table->timestamps();

                // relationships
                $table->foreign('cos_kode')->references('id_costomer')->on('costomer')->onDelete('cascade');
                $table->foreign('kry_kode')->references('kry_kode')->on('karyawan')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
