<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Karyawan;
use App\Models\KetersediaanSparepart;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeknisiSparepartTest extends TestCase
{
    use RefreshDatabase;

    private function createCustomerAndTransaksi(string $transKode = 'TRX-101'): void
    {
        Customer::create([
            'id_costomer' => 'CUS-001',
            'cos_nama' => 'Budi Customer',
        ]);

        Transaksi::create([
            'trans_kode' => $transKode,
            'cos_kode' => 'CUS-001',
            'trans_status' => 'Proses',
        ]);
    }

    public function test_teknisi_can_view_my_orders_page(): void
    {
        $teknisi = Karyawan::create([
            'kry_username' => 'teknisi_1',
            'kry_pswd' => bcrypt('password'),
            'kry_nama' => 'Teknisi Satu',
            'kry_level' => 'Teknisi',
            'kry_status' => 1,
        ]);

        $this->createCustomerAndTransaksi('TRX-101');

        KetersediaanSparepart::create([
            'trans_kode' => 'TRX-101',
            'cos_nama' => 'Budi Customer',
            'barang_nama' => 'LCD iPhone 11',
            'ketersediaan' => 'tidak_ada',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($teknisi)->get(route('teknisi.my_orders'));

        $response->assertStatus(200);
        $response->assertSee('Order Sparepart Saya');
        $response->assertSee('LCD iPhone 11');
        $response->assertSee('TRX-101');
    }

    public function test_teknisi_can_order_sparepart_via_ajax(): void
    {
        $teknisi = Karyawan::create([
            'kry_username' => 'teknisi_2',
            'kry_pswd' => bcrypt('password'),
            'kry_nama' => 'Teknisi Dua',
            'kry_level' => 'Teknisi',
            'kry_status' => 1,
        ]);

        $this->createCustomerAndTransaksi('TRX-999');

        $response = $this->actingAs($teknisi)
            ->postJson(route('teknisi.order_sparepart'), [
                'trans_kode' => 'TRX-999',
                'cos_nama' => 'Budi Customer',
                'barang_nama' => 'Baterai Samsung A52',
                'ketersediaan' => 'tidak_ada',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('ketersediaan_sparepart', [
            'trans_kode' => 'TRX-999',
            'barang_nama' => 'Baterai Samsung A52',
            'status' => 'menunggu',
        ]);
    }

    public function test_teknisi_cannot_access_admin_ketersediaan_sparepart(): void
    {
        $teknisi = Karyawan::create([
            'kry_username' => 'teknisi_3',
            'kry_pswd' => bcrypt('password'),
            'kry_nama' => 'Teknisi Tiga',
            'kry_level' => 'Teknisi',
            'kry_status' => 1,
        ]);

        $response = $this->actingAs($teknisi)->get(url('Admin/ketersediaan_sparepart'));

        // Teknisi should be redirected because of CheckRole middleware
        $response->assertRedirect('/Teknisi');
        $response->assertSessionHas('gagal', 'Anda tidak memiliki akses ke halaman tersebut.');
    }

    public function test_teknisi_can_save_tindakan_with_keterangan(): void
    {
        $teknisi = Karyawan::create([
            'kry_username' => 'teknisi_4',
            'kry_pswd' => bcrypt('password'),
            'kry_nama' => 'Teknisi Empat',
            'kry_level' => 'Teknisi',
            'kry_status' => 1,
        ]);

        $this->createCustomerAndTransaksi('TRX-TDKN-01');

        $response = $this->actingAs($teknisi)
            ->post(route('teknisi.save_tindakan'), [
                'trans_kode' => 'TRX-TDKN-01',
                'tindakan' => ['Mengganti LCD'],
                'qty' => [1],
                'subtot' => [350000],
                'ket' => ['LCD Original OEM'],
            ]);

        $response->assertRedirect(route('teknisi.index'));
        $response->assertSessionHas('sukses', 'Tindakan berhasil disimpan');

        $this->assertDatabaseHas('tindakan', [
            'trans_kode' => 'TRX-TDKN-01',
            'tdkn_barang' => 'Mengganti LCD',
            'tdkn_qty' => 1,
            'tdkn_subtot' => 350000,
            'tdkn_ket' => 'LCD Original OEM',
        ]);
    }
}
