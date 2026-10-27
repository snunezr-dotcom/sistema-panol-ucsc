<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
/* Usuarios ficticios del sistema*/
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new \RuntimeException('Demo users can only be seeded in local or testing environments.');
        }

        $users = [
            [
                'name' => 'Administrador',
                'rut' => '22222222',
                'password' => '2222',
                'is_admin' => true,
            ],
            [
                'name' => 'Profesor Lorenzo',    
                'rut' => '18276543',
                'password' => '1234',
                'is_admin' => false,
            ],
            [
                'name' => 'Profesor Demo 2',
                'rut' => '19876543',
                'password' => '5678',
                'is_admin' => false,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['rut' => $user['rut']],
                $user,
            );
        }
    }
}
