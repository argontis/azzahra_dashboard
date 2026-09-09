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
        if (! Schema::hasTable('vocer')) {
            Schema::create('vocer', function (Blueprint $table) {
                $table->id('voc_kode');
                $table->unsignedBigInteger('trans_kode')->nullable();
                $table->decimal('voc_jumlah', 15, 2)->default(0);
                $table->date('voc_tanggal')->nullable();
                $table->time('voc_jam')->nullable();
                $table->string('voc_status')->nullable();
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
        Schema::dropIfExists('vocers');
    }
};
