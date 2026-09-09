<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sender_id');   // kry_kode pengirim
                $table->string('sender_nama');
                $table->string('sender_level');
                $table->string('target_role');             // 'all' or specific role e.g. 'Teknisi'
                $table->string('judul');
                $table->text('isi');
                $table->timestamps();
            });
        }

        // Tabel untuk menandai siapa saja yang sudah membaca pesan
        Schema::create('message_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('reader_id');   // kry_kode pembaca
            $table->timestamp('read_at')->useCurrent();
            $table->unique(['message_id', 'reader_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_reads');
        Schema::dropIfExists('messages');
    }
};
