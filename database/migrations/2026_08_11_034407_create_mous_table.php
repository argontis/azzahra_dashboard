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
        Schema::create('mous', function (Blueprint $table) {
            $table->id('mou_id');
            $table->string('file_name');
            $table->string('lokasi')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('customer')->nullable();
            $table->text('intro_text')->nullable();
            $table->text('terms')->nullable();
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->string('kry_kode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mous');
    }
};
