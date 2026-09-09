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
        if (! Schema::hasTable('pencatatan')) {
            Schema::create('pencatatan', function (Blueprint $table) {
                $table->id('pencatatan_id');
                $table->string('batch_id')->nullable();
                $table->string('nama_barang')->nullable();
                $table->integer('qty')->default(0);
                $table->decimal('harga_satuan', 15, 2)->default(0);
                $table->decimal('total', 15, 2)->default(0);
                $table->date('tanggal')->nullable();
                $table->string('gambar')->nullable();
                $table->string('kategori_global')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencatatans');
    }
};
