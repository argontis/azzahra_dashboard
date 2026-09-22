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
            if (! Schema::hasColumn('costomer', 'username')) {
                $table->string('username')->nullable()->after('cos_nama');
            }
            if (! Schema::hasColumn('costomer', 'password')) {
                $table->string('password')->nullable()->after('username');
            }
            if (! Schema::hasColumn('costomer', 'cos_tgl_lahir')) {
                $table->date('cos_tgl_lahir')->nullable()->after('cos_keterangan');
            }
            if (! Schema::hasColumn('costomer', 'cos_pswd_type')) {
                $table->string('cos_pswd_type')->nullable()->after('cos_pswd');
            }
            if (! Schema::hasColumn('costomer', 'cos_pswd_canvas')) {
                $table->text('cos_pswd_canvas')->nullable()->after('cos_pswd_type');
            }
            if (! Schema::hasColumn('costomer', 'cos_cabang')) {
                $table->string('cos_cabang')->nullable()->after('cos_alamat');
            }
            if (! Schema::hasColumn('costomer', 'cos_device')) {
                $table->string('cos_device')->nullable()->after('cos_cabang');
            }
            if (! Schema::hasColumn('costomer', 'cos_tanggal')) {
                $table->date('cos_tanggal')->nullable()->after('cos_tgl_lahir');
            }
            if (! Schema::hasColumn('costomer', 'cos_jam')) {
                $table->time('cos_jam')->nullable()->after('cos_tanggal');
            }
            if (! Schema::hasColumn('costomer', 'cos_poin')) {
                $table->integer('cos_poin')->default(0)->after('cos_jam');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('costomer', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'password',
                'cos_tgl_lahir',
                'cos_pswd_type',
                'cos_pswd_canvas',
                'cos_cabang',
                'cos_device',
                'cos_tanggal',
                'cos_jam',
                'cos_poin',
            ]);
        });
    }
};
