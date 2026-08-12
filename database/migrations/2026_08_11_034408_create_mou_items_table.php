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
        Schema::create('mou_items', function (Blueprint $table) {
            $table->increments('item_id');
            $table->integer('mou_id');
            $table->integer('item_no');
            $table->text('spesifikasi');
            $table->decimal('qty', 10, 2)->default(0.00);
            $table->decimal('harga', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2)->default(0.00);

            $table->index('mou_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mou_items');
    }
};
