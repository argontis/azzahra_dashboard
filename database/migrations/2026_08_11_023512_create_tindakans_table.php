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
        if (! Schema::hasTable('tindakan')) {
            Schema::create('tindakan', function (Blueprint $table) {
                $table->id('tdkn_kode');
                $table->unsignedBigInteger('trans_kode')->nullable();
                $table->string('tdkn_barang')->nullable();
                $table->decimal('tdkn_harga', 15, 2)->default(0);
                $table->integer('tdkn_qty')->default(1);
                $table->decimal('tdkn_subtot', 15, 2)->default(0);
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
        Schema::dropIfExists('tindakans');
    }
};
