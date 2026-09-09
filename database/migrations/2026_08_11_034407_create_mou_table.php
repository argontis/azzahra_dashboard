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
        if (! Schema::hasTable('mou')) {
            Schema::create('mou', function (Blueprint $table) {
                $table->increments('mou_id');
                $table->string('file_name', 255);
                $table->text('intro_text');
                $table->text('terms')->nullable();
                $table->string('lokasi', 50);
                $table->date('tanggal');
                $table->string('customer', 255);
                $table->decimal('grand_total', 15, 2)->default(0.00);
                $table->string('kry_kode', 20)->nullable();
                $table->dateTime('created_at');

                $table->index('kry_kode');

            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mous');
    }
};
