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
        if (! Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('isi');
                $table->string('tipe')->default('maintenance'); // maintenance, penting, info
                $table->dateTime('mulai_pada')->nullable();
                $table->dateTime('selesai_pada')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->string('status')->default('active'); // active, inactive
                $table->timestamps();
            });
        }

        Schema::create('announcement_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('announcement_id');
            $table->unsignedBigInteger('kry_kode');
            $table->timestamp('read_at')->useCurrent();

            $table->foreign('announcement_id')->references('id')->on('announcements')->onDelete('cascade');
            $table->unique(['announcement_id', 'kry_kode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_reads');
        Schema::dropIfExists('announcements');
    }
};
