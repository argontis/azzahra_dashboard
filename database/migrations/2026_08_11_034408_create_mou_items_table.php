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
            $table->id('item_id');
            $table->unsignedBigInteger('mou_id');
            $table->integer('item_no');
            $table->string('spesifikasi');
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
            
            $table->foreign('mou_id')->references('mou_id')->on('mous')->onDelete('cascade');
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
