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
        if (! Schema::hasTable('costomer')) {
            Schema::create('costomer', function (Blueprint $table) {
                $table->id('id_costomer');
                $table->string('cos_nama');
                $table->string('cos_alamat')->nullable();
                $table->string('cos_hp')->nullable();
                $table->string('cos_tipe')->nullable();
                $table->string('cos_model')->nullable();
                $table->string('cos_no_seri')->nullable();
                $table->string('cos_asesoris')->nullable();
                $table->string('cos_status')->nullable();
                $table->string('cos_pswd')->nullable();
                $table->text('cos_keluhan')->nullable();
                $table->text('cos_keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('costomer');
    }
};
