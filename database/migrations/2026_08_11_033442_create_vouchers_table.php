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
        if (! Schema::hasTable('vouchers')) {
            Schema::create('vouchers', function (Blueprint $table) {
                $table->id('voucher_id');
                $table->string('voucher_code')->unique();
                $table->text('description')->nullable();
                $table->decimal('discount_percent', 5, 2);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->integer('max_usage')->default(1);
                $table->string('voucher_gambar')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
