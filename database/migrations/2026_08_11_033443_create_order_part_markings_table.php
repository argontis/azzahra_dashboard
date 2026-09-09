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
        if (!Schema::hasTable('order_part_markings')) {
            Schema::create('order_part_markings', function (Blueprint $table) {
            $table->id();
            $table->string('trans_kode');
            $table->enum('is_ordered', ['yes', 'no'])->default('no');
            $table->string('rma_number')->nullable();
            $table->date('end_warranty_date')->nullable();
            $table->timestamps();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_part_markings');
    }
};
