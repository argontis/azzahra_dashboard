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
        Schema::table('tindakan', function (Blueprint $table) {
            if (! Schema::hasColumn('tindakan', 'tdkn_ket')) {
                $table->text('tdkn_ket')->nullable()->after('tdkn_subtot');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tindakan', function (Blueprint $table) {
            if (Schema::hasColumn('tindakan', 'tdkn_ket')) {
                $table->dropColumn('tdkn_ket');
            }
        });
    }
};
