<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            if (! Schema::hasColumn('karyawan', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (! Schema::hasColumn('karyawan', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
            if (! Schema::hasColumn('karyawan', 'kry_status')) {
                $table->boolean('kry_status')->default(1);
            }
            if (! Schema::hasColumn('karyawan', 'kry_telp')) {
                $table->string('kry_telp', 20)->nullable();
            }
            if (! Schema::hasColumn('karyawan', 'kry_join_date')) {
                $table->date('kry_join_date')->nullable();
            }
        });

        // Sync legacy column data if present
        if (Schema::hasColumn('karyawan', 'kry_tlp') && Schema::hasColumn('karyawan', 'kry_telp')) {
            DB::statement("UPDATE karyawan SET kry_telp = kry_tlp WHERE (kry_telp IS NULL OR kry_telp = '') AND kry_tlp IS NOT NULL");
        }
        if (Schema::hasColumn('karyawan', 'kry_tgl_masuk') && Schema::hasColumn('karyawan', 'kry_join_date')) {
            DB::statement('UPDATE karyawan SET kry_join_date = kry_tgl_masuk WHERE kry_join_date IS NULL AND kry_tgl_masuk IS NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
