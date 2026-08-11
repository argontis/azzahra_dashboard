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
        Schema::create('order_list', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_kode')->nullable();
            $table->unsignedBigInteger('cos_kode')->nullable();
            $table->unsignedBigInteger('kry_kode')->nullable();
            $table->decimal('trans_total', 15, 2)->default(0);
            $table->decimal('trans_discount', 15, 2)->default(0);
            $table->date('trans_tanggal')->nullable();
            $table->string('trans_status')->default('itemSubmitted');
            $table->string('merek')->nullable();
            $table->string('device')->nullable();
            $table->string('status_garansi')->nullable();
            $table->string('seri')->nullable();
            $table->text('ket_keluhan')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();

            $table->foreign('trans_kode')->references('trans_kode')->on('transaksi')->onDelete('cascade');
            $table->foreign('cos_kode')->references('id_costomer')->on('costomer')->onDelete('cascade');
            $table->foreign('kry_kode')->references('kry_kode')->on('karyawan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_lists');
    }
};
