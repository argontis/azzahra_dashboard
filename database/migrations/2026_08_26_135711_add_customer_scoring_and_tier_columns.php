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
        Schema::table('costomer', function (Blueprint $table) {
            if (! Schema::hasColumn('costomer', 'cos_score')) {
                $table->unsignedTinyInteger('cos_score')->default(1)->after('cos_poin');
            }
            if (! Schema::hasColumn('costomer', 'cos_tier')) {
                $table->string('cos_tier', 20)->default('reguler')->after('cos_score');
            }
            if (! Schema::hasColumn('costomer', 'total_transaksi')) {
                $table->unsignedInteger('total_transaksi')->default(0)->after('cos_tier');
            }
        });

        if (Schema::hasTable('transaksi') && ! Schema::hasColumn('transaksi', 'tipe_layanan')) {
            Schema::table('transaksi', function (Blueprint $table) {
                $table->string('tipe_layanan', 30)->default('walk-in')->after('trans_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('costomer', function (Blueprint $table) {
            $table->dropColumn(['cos_score', 'cos_tier', 'total_transaksi']);
        });

        if (Schema::hasTable('transaksi') && Schema::hasColumn('transaksi', 'tipe_layanan')) {
            Schema::table('transaksi', function (Blueprint $table) {
                $table->dropColumn('tipe_layanan');
            });
        }
    }
};
