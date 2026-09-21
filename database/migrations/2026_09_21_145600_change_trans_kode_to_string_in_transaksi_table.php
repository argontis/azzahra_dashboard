<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("SET SESSION sql_mode = ''");
        DB::statement('ALTER TABLE transaksi MODIFY trans_kode VARCHAR(50) NOT NULL');
        DB::statement('ALTER TABLE costomer MODIFY cos_pswd VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, MODIFY cos_pswd_canvas LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
