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
        if (!Schema::hasTable('arsip')) {
            Schema::create('arsip', function (Blueprint $table) {
            $table->id('arsip_id');
            $table->string('tipe')->nullable();
            $table->string('nama')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('tipe_detail')->nullable();
            $table->text('kerusakan')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};
