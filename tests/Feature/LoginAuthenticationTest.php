<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginAuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_page_is_accessible()
    {
        $response = $this->get('/Auth');
        $response->assertStatus(200);
    }

    public function test_testadmin_can_login_and_redirects_to_admin()
    {
        $response = $this->post('/Auth/login', [
            'username' => 'testadmin',
            'pswd' => 'password123',
        ]);

        $response->assertRedirect('/Admin');
        $this->assertAuthenticated();
    }

    public function test_testhr_can_login_and_redirects_to_hr()
    {
        $response = $this->post('/Auth/login', [
            'username' => 'testhr',
            'pswd' => 'password123',
        ]);

        $response->assertRedirect('/HR');
        $this->assertAuthenticated();
    }

    public function test_testcs_can_login_and_redirects_to_service()
    {
        $response = $this->post('/Auth/login', [
            'username' => 'testcs',
            'pswd' => 'password123',
        ]);

        $response->assertRedirect('/Service');
        $this->assertAuthenticated();
    }

    public function test_testkasir_can_login_and_redirects_to_kasir()
    {
        $response = $this->post('/Auth/login', [
            'username' => 'testkasir',
            'pswd' => 'password123',
        ]);

        $response->assertRedirect('/Kasir');
        $this->assertAuthenticated();
    }

    public function test_testteknisi_can_login_and_redirects_to_teknisi()
    {
        $response = $this->post('/Auth/login', [
            'username' => 'testteknisi',
            'pswd' => 'password123',
        ]);

        $response->assertRedirect('/Teknisi');
        $this->assertAuthenticated();
    }
}
