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
        if (!Schema::hasTable('transaksi_return')) {
            Schema::create('transaksi_return', function (Blueprint $table) {
            $table->id('ret_kode');
            $table->unsignedBigInteger('trans_kode')->nullable();
            $table->unsignedBigInteger('dtl_kode')->nullable();
            $table->decimal('ret_jml', 15, 2)->default(0);
            $table->date('ret_tanggal')->nullable();
            $table->time('ret_jam')->nullable();
            $table->timestamps();

            $table->foreign('trans_kode')->references('trans_kode')->on('transaksi')->onDelete('cascade');
            $table->foreign('dtl_kode')->references('dtl_kode')->on('transaksi_detail')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_returns');
    }
};
