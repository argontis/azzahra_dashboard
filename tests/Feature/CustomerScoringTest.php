<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Karyawan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CustomerScoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_hr_user_can_update_customer_score_via_flask_api(): void
    {
        // 1. Mock Flask API HTTP response
        Http::fake([
            'http://127.0.0.1:5000/api/scoring' => Http::response([
                'status' => 'success',
                'skor_pelanggan' => 5,
            ], 200),
        ]);

        // 2. Create HR user
        $hrUser = Karyawan::create([
            'kry_username' => 'hr_test',
            'kry_pswd' => bcrypt('password123'),
            'kry_nama' => 'HR Tester',
            'kry_level' => 'HR',
            'kry_status' => 1,
        ]);

        // 3. Create sample Customer
        $customer = Customer::create([
            'id_costomer' => 'CUS-TEST-001',
            'cos_nama' => 'Pelanggan Test',
            'cos_score' => 1,
            'cos_tier' => 'reguler',
        ]);

        // 4. Act as HR user and call update-skor route
        $response = $this->actingAs($hrUser)
            ->postJson("/pelanggan/{$customer->id_costomer}/update-skor");

        // 5. Assertions
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'skor_baru' => 5,
                'tier_baru' => 'prioritas',
            ]);

        $this->assertDatabaseHas('costomer', [
            'id_costomer' => 'CUS-TEST-001',
            'cos_score' => 5,
            'cos_tier' => 'prioritas',
        ]);
    }
}
