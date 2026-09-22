<?php

namespace Tests\Feature;

use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Ensure sample users exist for each role level
        $sampleData = [
            ['kry_level' => 'Admin', 'kry_username' => 'admin_test_user', 'kry_pswd' => Hash::make('password'), 'kry_nama' => 'Admin User'],
            ['kry_level' => 'Teknisi', 'kry_username' => 'teknisi_test_user', 'kry_pswd' => Hash::make('password'), 'kry_nama' => 'Teknisi User'],
            ['kry_level' => 'Customer Service', 'kry_username' => 'service_test_user', 'kry_pswd' => Hash::make('password'), 'kry_nama' => 'Service User'],
            ['kry_level' => 'Kasir', 'kry_username' => 'kasir_test_user', 'kry_pswd' => Hash::make('password'), 'kry_nama' => 'Kasir User'],
            ['kry_level' => 'HR', 'kry_username' => 'hr_test_user', 'kry_pswd' => Hash::make('password'), 'kry_nama' => 'HR User'],
            ['kry_level' => 'Magang / PKL', 'kry_username' => 'magang_test_user', 'kry_pswd' => Hash::make('password'), 'kry_nama' => 'Magang User'],
            ['kry_level' => 'Pimpinan', 'kry_username' => 'pimpinan_test_user', 'kry_pswd' => Hash::make('password'), 'kry_nama' => 'Pimpinan User'],
        ];
        foreach ($sampleData as $data) {
            Karyawan::firstOrCreate(['kry_username' => $data['kry_username']], $data);
        }
    }

    /**
     * Test that each role can access its dashboard without errors.
     */
    public function test_role_based_dashboard_access(): void
    {
        $roles = [
            'Admin' => '/Admin',
            'Teknisi' => '/Teknisi',
            'Customer Service' => '/Service',
            'Kasir' => '/Kasir',
            'HR' => '/HR',
            'Magang / PKL' => '/Teknisi',
            'Pimpinan' => '/Admin',
        ];

        foreach ($roles as $roleName => $url) {
            $user = Karyawan::where('kry_level', $roleName)->first();
            $this->assertNotNull(
                $user,
                "No user found for role {$roleName}. Ensure the database contains a sample record."
            );

            // Authenticate as the user.
            $this->actingAs($user);

            // Access the dashboard route.
            $response = $this->get($url);
            $response->assertStatus(200);
        }
    }
}
