<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        try {
            DB::statement('ALTER TABLE ketersediaan_sparepart DROP FOREIGN KEY ketersediaan_sparepart_trans_kode_foreign');
        } catch (Throwable $e) {
        }

        try {
            DB::statement("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");
            DB::statement("UPDATE transaksi SET trans_tanggal = NULL WHERE trans_tanggal = '0000-00-00' OR CAST(trans_tanggal AS CHAR) = '0000-00-00'");
            DB::statement("UPDATE transaksi SET cos_tanggal = NULL WHERE cos_tanggal = '0000-00-00' OR CAST(cos_tanggal AS CHAR) = '0000-00-00'");
        } catch (Throwable $e) {
        }

        DB::statement('ALTER TABLE transaksi MODIFY trans_kode VARCHAR(50) NOT NULL');
        DB::statement('ALTER TABLE costomer MODIFY cos_pswd VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, MODIFY cos_pswd_canvas LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL');

        try {
            DB::statement('ALTER TABLE ketersediaan_sparepart ADD CONSTRAINT ketersediaan_sparepart_trans_kode_foreign FOREIGN KEY (trans_kode) REFERENCES transaksi (trans_kode) ON DELETE CASCADE');
        } catch (Throwable $e) {
        }
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
