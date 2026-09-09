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
        if (! Schema::hasTable('transaksi_detail')) {
            Schema::create('transaksi_detail', function (Blueprint $table) {
                $table->id('dtl_kode');
                $table->unsignedBigInteger('trans_kode')->nullable();
                $table->unsignedBigInteger('kry_kode')->nullable();
                $table->decimal('dtl_jml_bayar', 15, 2)->default(0);
                $table->string('dtl_jenis_bayar')->nullable();
                $table->string('dtl_bank')->nullable();
                $table->string('dtl_status')->nullable();
                $table->date('dtl_tanggal')->nullable();
                $table->time('dtl_jam')->nullable();
                $table->string('dtl_stt_stor')->nullable();
                $table->string('dtl_payment_method')->nullable();
                $table->string('dtl_transfer_status')->nullable();
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
        Schema::dropIfExists('transaksi_details');
    }
};
