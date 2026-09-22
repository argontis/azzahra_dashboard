<?php

namespace Tests\Feature;

use App\Models\Karyawan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ServiceQueueTest extends TestCase
{
    use DatabaseTransactions;

    public function test_service_antrean_loads_successfully_for_cs()
    {
        $cs = Karyawan::where('kry_level', 'Customer Service')->first();
        if (! $cs) {
            $cs = Karyawan::where('kry_username', 'testcs')->first();
        }

        $response = $this->actingAs($cs)->get('/Service');
        $response->assertStatus(200);

        $response = $this->actingAs($cs)->get('/Service/antrean/baru');
        $response->assertStatus(200);

        $response = $this->actingAs($cs)->get('/Service/antrean/diproses');
        $response->assertStatus(200);

        $response = $this->actingAs($cs)->get('/Service/antrean/selesai');
        $response->assertStatus(200);
    }

    public function test_quickservice_antrean_loads_successfully()
    {
        $admin = Karyawan::where('kry_username', 'testadmin')->first()
            ?? Karyawan::where('kry_level', 'Admin')->first();
        $response = $this->actingAs($admin)->get('/QuickService/antrean/baru');
        $response->assertStatus(200);
    }

    public function test_teknisi_dashboard_loads_successfully()
    {
        $teknisi = Karyawan::where('kry_username', 'testteknisi')->first()
            ?? Karyawan::where('kry_level', 'Teknisi')->first();
        $response = $this->actingAs($teknisi)->get('/Teknisi');
        $response->assertStatus(200);
    }
}
