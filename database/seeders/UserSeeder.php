<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
/* Usuarios ficticios del sistema*/
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new \RuntimeException('Demo users can only be seeded in local or testing environments.');
        }

        $this->call(RoleSeeder::class);

        $users = [
            [
                'name' => 'Administrador',
                'rut' => '22222222',
                'password' => '2222',
                'role' => 'Administrador',
            ],
            [
                'name' => 'Profesor Lorenzo',
                'rut' => '18276543',
                'password' => '1234',
                'role' => 'Profesores',
            ],
            [
                'name' => 'Profesor Demo 2',
                'rut' => '19876543',
                'password' => '5678',
                'role' => 'Profesores',
            ],
        ];

        foreach ($users as $user) {
            $role = Role::where('name', $user['role'])->firstOrFail();
            unset($user['role']);

            User::updateOrCreate(
                ['rut' => $user['rut']],
                [...$user, 'role_id' => $role->id, 'is_admin' => $role->name === 'Administrador'],
            );
        }
    }
}
