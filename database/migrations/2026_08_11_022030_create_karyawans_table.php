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
        if (! Schema::hasTable('karyawan')) {
            Schema::create('karyawan', function (Blueprint $table) {
                $table->id('kry_kode');
                $table->string('kry_username')->unique();
                $table->string('kry_pswd');
                $table->string('kry_nama');
                $table->string('kry_level')->default('Kasir'); // Admin, Kasir, Customer Service, Teknisi, HR
                $table->string('kry_telp')->nullable();
                $table->text('kry_alamat')->nullable();
                $table->date('kry_join_date')->nullable();
                $table->boolean('kry_status')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
