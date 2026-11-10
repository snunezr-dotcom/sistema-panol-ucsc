<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Database\Seeders\RoleSeeder;
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
            'role_id' => Role::create(['name' => 'Administrador'])->id,
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

    public function test_language_change_returns_json_and_persists_in_session(): void
    {
        $this->post('/language', ['locale' => 'en'], [
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->assertOk()->assertJson(['locale' => 'en']);

        $this->assertSame('en', session('locale'));
        $this->get('/login')->assertOk()->assertSee('<html lang="en">', false);
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

    public function test_role_seeder_creates_only_the_two_supported_roles(): void
    {
        $this->seed(RoleSeeder::class);

        $this->assertSame(
            ['Administrador', 'Profesores'],
            Role::query()->orderBy('name')->pluck('name')->all(),
        );
    }

    public function test_user_seeder_assigns_admin_and_teacher_roles(): void
    {
        $this->seed(UserSeeder::class);

        $this->assertTrue(User::where('rut', '22222222')->firstOrFail()->isAdministrator());
        $this->assertSame('Profesores', User::where('rut', '18276543')->firstOrFail()->role->name);
        $this->assertSame('Profesores', User::where('rut', '19876543')->firstOrFail()->role->name);
    }
}
