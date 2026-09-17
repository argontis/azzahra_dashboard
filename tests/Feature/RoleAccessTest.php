<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;

class RoleAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Ensure sample users exist for each role level
        $sampleData = [
            ['kry_level' => 1, 'kry_username' => 'admin_user', 'kry_pswd' => \Illuminate\Support\Facades\Hash::make('password'), 'kry_nama' => 'Admin User'],
            ['kry_level' => 2, 'kry_username' => 'teknisi_user', 'kry_pswd' => \Illuminate\Support\Facades\Hash::make('password'), 'kry_nama' => 'Teknisi User'],
            ['kry_level' => 3, 'kry_username' => 'service_user', 'kry_pswd' => \Illuminate\Support\Facades\Hash::make('password'), 'kry_nama' => 'Service User'],
            ['kry_level' => 4, 'kry_username' => 'kasir_user', 'kry_pswd' => \Illuminate\Support\Facades\Hash::make('password'), 'kry_nama' => 'Kasir User'],
            ['kry_level' => 5, 'kry_username' => 'hr_user', 'kry_pswd' => \Illuminate\Support\Facades\Hash::make('password'), 'kry_nama' => 'HR User'],
            ['kry_level' => 6, 'kry_username' => 'magang_user', 'kry_pswd' => \Illuminate\Support\Facades\Hash::make('password'), 'kry_nama' => 'Magang User'],
            ['kry_level' => 7, 'kry_username' => 'pimpinan_user', 'kry_pswd' => \Illuminate\Support\Facades\Hash::make('password'), 'kry_nama' => 'Pimpinan User'],
        ];
        foreach ($sampleData as $data) {
            \App\Models\Karyawan::firstOrCreate(['kry_level' => $data['kry_level']], $data);
        }
    }

    /**
     * Test that each role can access its dashboard without errors.
     */
    public function test_role_based_dashboard_access(): void
    {
        $roles = [
            // level => url prefix (adjust according to your route definitions)
            1 => '/Admin',          // Admin
            2 => '/Teknisi',        // Teknisi
            3 => '/Service',        // Customer Service
            4 => '/Kasir',          // Kasir
            5 => '/HR',             // HR
            6 => '/Magang',         // Magang / PKL
            7 => '/Pimpinan',       // Pimpinan
        ];

        foreach ($roles as $level => $url) {
            // Retrieve a sample user for the role.
            $user = Karyawan::where('kry_level', $level)->first();
            $this->assertNotNull(
                $user,
                "No user found for role level {$level}. Ensure the database contains a sample record."
            );

            // Authenticate as the user.
            $this->actingAs($user);

            // Access the dashboard route.
            $response = $this->get($url);
            $response->assertStatus(200);
        }
    }
}
?>
