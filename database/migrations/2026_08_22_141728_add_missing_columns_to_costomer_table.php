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
            $table->string('username')->nullable()->after('cos_nama');
            $table->string('password')->nullable()->after('username');
            $table->date('cos_tgl_lahir')->nullable()->after('cos_keterangan');
            $table->string('cos_pswd_type')->nullable()->after('cos_pswd');
            $table->text('cos_pswd_canvas')->nullable()->after('cos_pswd_type');
            $table->string('cos_cabang')->nullable()->after('cos_alamat');
            $table->string('cos_device')->nullable()->after('cos_cabang');
            $table->date('cos_tanggal')->nullable()->after('cos_tgl_lahir');
            $table->time('cos_jam')->nullable()->after('cos_tanggal');
            $table->integer('cos_poin')->default(0)->after('cos_jam');
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
