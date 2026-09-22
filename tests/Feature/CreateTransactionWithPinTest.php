<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Karyawan;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateTransactionWithPinTest extends TestCase
{
    use DatabaseTransactions;

    public function test_cs_can_create_new_transaction_with_pin_password_type()
    {
        $cs = Karyawan::where('kry_level', 'Customer Service')->first();
        if (! $cs) {
            $cs = Karyawan::where('kry_username', 'testcs')->first();
        }

        $postData = [
            'nama' => 'Budi Santoso PIN Test',
            'tlp' => '081299988877',
            'alamat' => 'Jl. Mawar No. 12',
            'cabang' => 'Tegal',
            'device' => 'Laptop Asus ROG',
            'type' => 'Asus ROG Strix G15',
            'model' => 'G513IH',
            'seri' => 'ROG998877',
            'asesoris' => 'Charger Original',
            'status' => 'OOW',
            'pswd_type' => 'pin',
            'pswd' => '889900',
            'keluhan' => 'Layar blue screen saat gaming',
            'ket' => 'Unit bersih, segel utuh',
        ];

        $response = $this->actingAs($cs)->from('/Service/antrean/baru')->post(route('service.save_trans'), $postData);
        $response->assertRedirect(route('service.antrean', 'baru'));

        // Verify Customer record
        $customer = Customer::where('cos_hp', '081299988877')->latest('id_costomer')->first();
        $this->assertNotNull($customer);
        $this->assertEquals('pin', $customer->cos_pswd_type);
        $this->assertEquals('889900', $customer->cos_pswd);
        $this->assertEquals('Budi Santoso PIN Test', $customer->cos_nama);

        // Verify Transaksi record
        $transaksi = Transaksi::where('cos_kode', $customer->id_costomer)->first();
        $this->assertNotNull($transaksi);
        $this->assertEquals('Baru', $transaksi->trans_status);
    }
}
