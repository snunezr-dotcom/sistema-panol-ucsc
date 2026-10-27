<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_log_in_with_their_rut_and_password(): void
    {
        $user = User::create([
            'name' => 'Administrador',
            'rut' => '22222222',
            'password' => '2222',
            'is_admin' => true,
        ]);

        $response = $this->post('/login', [
            'rut' => '22222222',
            'password' => '2222',
        ]);

        $response->assertRedirect('/carrito');
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_do_not_log_in(): void
    {
        $response = $this->from('/login')->post('/login', [
            'rut' => '22222222',
            'password' => 'incorrecta',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_cart_requires_authentication(): void
    {
        $this->get('/carrito')->assertRedirect('/login');
    }

    public function test_seeded_teachers_can_log_in_and_see_their_name_in_the_cart(): void
    {
        $this->seed(UserSeeder::class);

        foreach ([
            ['rut' => '18276543', 'password' => '1234', 'name' => 'Profesor Lorenzo'],
            ['rut' => '19876543', 'password' => '5678', 'name' => 'Profesor Demo 2'],
        ] as $teacher) {
            $this->post('/login', [
                'rut' => $teacher['rut'],
                'password' => $teacher['password'],
            ])->assertRedirect('/carrito');

            $this->get('/carrito')->assertOk()->assertSeeText($teacher['name']);
            $this->post('/logout')->assertRedirect('/login');
        }
    }
}
