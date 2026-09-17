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
        Schema::table('transaksi_detail', function (Blueprint $table) {
            if (! Schema::hasColumn('transaksi_detail', 'xendit_invoice_id')) {
                $table->string('xendit_invoice_id')->nullable();
            }
            if (! Schema::hasColumn('transaksi_detail', 'xendit_invoice_url')) {
                $table->text('xendit_invoice_url')->nullable();
            }
            if (! Schema::hasColumn('transaksi_detail', 'xendit_status')) {
                $table->string('xendit_status')->nullable();
            }
            if (! Schema::hasColumn('transaksi_detail', 'xendit_payment_method')) {
                $table->string('xendit_payment_method')->nullable();
            }
            if (! Schema::hasColumn('transaksi_detail', 'xendit_paid_at')) {
                $table->timestamp('xendit_paid_at')->nullable();
            }
            if (! Schema::hasColumn('transaksi_detail', 'dtl_payment_method')) {
                $table->string('dtl_payment_method')->nullable();
            }
            if (! Schema::hasColumn('transaksi_detail', 'dtl_transfer_status')) {
                $table->string('dtl_transfer_status')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_detail', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_detail', 'xendit_invoice_id')) {
                $table->dropColumn('xendit_invoice_id');
            }
            if (Schema::hasColumn('transaksi_detail', 'xendit_invoice_url')) {
                $table->dropColumn('xendit_invoice_url');
            }
            if (Schema::hasColumn('transaksi_detail', 'xendit_status')) {
                $table->dropColumn('xendit_status');
            }
            if (Schema::hasColumn('transaksi_detail', 'xendit_payment_method')) {
                $table->dropColumn('xendit_payment_method');
            }
            if (Schema::hasColumn('transaksi_detail', 'xendit_paid_at')) {
                $table->dropColumn('xendit_paid_at');
            }
        });
    }
};
