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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('tindakan');
        Schema::dropIfExists('transaksi_detail');
        Schema::dropIfExists('vocer');
        Schema::dropIfExists('transaksi_return');
        Schema::dropIfExists('order_list');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('costomer');

        Schema::create('costomer', function (Blueprint $table) {
            $table->string('id_costomer')->primary();
            $table->string('cos_nama');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('cos_alamat')->nullable();
            $table->string('cos_hp')->nullable();
            $table->string('cos_cabang')->nullable();
            $table->string('cos_device')->nullable();
            $table->string('cos_tipe')->nullable();
            $table->string('cos_model')->nullable();
            $table->string('cos_no_seri')->nullable();
            $table->string('cos_asesoris')->nullable();
            $table->string('cos_status')->nullable();
            $table->string('cos_pswd')->nullable();
            $table->string('cos_pswd_type')->nullable();
            $table->text('cos_pswd_canvas')->nullable();
            $table->text('cos_keluhan')->nullable();
            $table->text('cos_keterangan')->nullable();
            $table->date('cos_tgl_lahir')->nullable();
            $table->date('cos_tanggal')->nullable();
            $table->time('cos_jam')->nullable();
            $table->integer('cos_poin')->default(0);
            $table->timestamps();
        });

        Schema::create('transaksi', function (Blueprint $table) {
            $table->string('trans_kode')->primary();
            $table->string('cos_kode');
            $table->string('kry_kode')->nullable();
            $table->string('trans_status')->default('Baru');
            $table->date('cos_tanggal')->nullable();
            $table->time('cos_jam')->nullable();
            $table->date('trans_tanggal')->nullable();
            $table->decimal('trans_discount', 15, 2)->default(0);
            $table->decimal('trans_total', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('tindakan', function (Blueprint $table) {
            $table->id('tdkn_kode');
            $table->string('trans_kode')->nullable();
            $table->string('tdkn_barang')->nullable();
            $table->decimal('tdkn_harga', 15, 2)->default(0);
            $table->integer('tdkn_qty')->default(1);
            $table->decimal('tdkn_subtot', 15, 2)->default(0);
            $table->date('tdkn_tanggal')->nullable();
            $table->time('tdkn_jam')->nullable();
            $table->timestamps();
        });

        Schema::create('order_list', function (Blueprint $table) {
            $table->id();
            $table->string('trans_kode')->nullable();
            $table->string('cos_kode')->nullable();
            $table->string('kry_kode')->nullable();
            $table->decimal('trans_total', 15, 2)->default(0);
            $table->decimal('trans_discount', 15, 2)->default(0);
            $table->date('trans_tanggal')->nullable();
            $table->string('trans_status')->default('itemSubmitted');
            $table->string('merek')->nullable();
            $table->string('device')->nullable();
            $table->string('status_garansi')->nullable();
            $table->string('seri')->nullable();
            $table->text('ket_keluhan')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id('dtl_kode');
            $table->string('trans_kode')->nullable();
            $table->string('kry_kode')->nullable();
            $table->decimal('dtl_jml_bayar', 15, 2)->default(0);
            $table->string('dtl_jenis_bayar')->nullable();
            $table->string('dtl_bank')->nullable();
            $table->string('dtl_status')->nullable();
            $table->date('dtl_tanggal')->nullable();
            $table->time('dtl_jam')->nullable();
            $table->string('dtl_stt_stor')->nullable();
            $table->string('dtl_payment_method')->nullable();
            $table->string('dtl_transfer_status')->nullable();
            $table->timestamps();
        });

        Schema::create('vocer', function (Blueprint $table) {
            $table->id('voc_kode');
            $table->string('trans_kode')->nullable();
            $table->decimal('voc_jumlah', 15, 2)->default(0);
            $table->date('voc_tanggal')->nullable();
            $table->string('voc_status')->nullable();
            $table->timestamps();
        });

        Schema::create('transaksi_return', function (Blueprint $table) {
            $table->id('ret_kode');
            $table->string('trans_kode')->nullable();
            $table->decimal('ret_jml', 15, 2)->default(0);
            $table->date('ret_tanggal')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
