<?php

namespace Tests\Feature;

use App\Models\Karyawan;
use App\Models\OrderList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPickupTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_pickup_completed_item(): void
    {
        $admin = Karyawan::create([
            'kry_username' => 'admin_test',
            'kry_pswd' => bcrypt('password123'),
            'kry_nama' => 'Admin Tester',
            'kry_level' => 'Admin',
            'kry_status' => 1,
        ]);

        $order = OrderList::create([
            'trans_kode' => 'TRTEST001',
            'cos_kode' => 'CUS001',
            'trans_total' => 100000,
            'trans_discount' => 0,
            'trans_tanggal' => now()->toDateString(),
            'trans_status' => 'service_completed',
            'merek' => 'ASUS',
            'device' => 'Laptop',
            'seri' => 'A409',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.order.pickup_item'), [
                'trans_kode' => 'TRTEST001',
                'order_type' => 'completed',
            ]);

        $response->assertRedirect(route('admin.order.index', 'completed'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('order_list', [
            'trans_kode' => 'TRTEST001',
            'trans_status' => 'completed_picked_up',
        ]);
    }

    public function test_admin_can_pickup_failed_item(): void
    {
        $admin = Karyawan::create([
            'kry_username' => 'admin_test2',
            'kry_pswd' => bcrypt('password123'),
            'kry_nama' => 'Admin Tester 2',
            'kry_level' => 'Admin',
            'kry_status' => 1,
        ]);

        $order = OrderList::create([
            'trans_kode' => 'TRTEST002',
            'cos_kode' => 'CUS002',
            'trans_total' => 50000,
            'trans_discount' => 0,
            'trans_tanggal' => now()->toDateString(),
            'trans_status' => 'service_failed',
            'merek' => 'Lenovo',
            'device' => 'Laptop',
            'seri' => 'Ideapad',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.order.pickup_item'), [
                'trans_kode' => 'TRTEST002',
                'order_type' => 'failed',
            ]);

        $response->assertRedirect(route('admin.order.index', 'failed'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('order_list', [
            'trans_kode' => 'TRTEST002',
            'trans_status' => 'failed_picked_up',
        ]);
    }
}
