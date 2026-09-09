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
        if (! Schema::hasTable('order_part_approvals')) {
            Schema::create('order_part_approvals', function (Blueprint $table) {
                $table->id('approval_id');
                $table->string('trans_kode');
                $table->string('type'); // oow, iw
                $table->string('approval_status')->default('pending'); // pending, approved, rejected
                $table->integer('approved_by')->nullable();
                $table->dateTime('approved_at')->nullable();
                $table->text('rejected_reason')->nullable();
                $table->dateTime('rejected_at')->nullable();
                $table->string('supplier_id')->nullable();
                $table->string('lead_time')->nullable();
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_part_approvals');
    }
};
