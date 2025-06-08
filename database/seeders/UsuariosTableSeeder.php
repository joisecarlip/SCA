<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;

class UsuariosTableSeeder extends Seeder
{
    public function run()
    {
        Usuarios::updateOrCreate(
            ['user_nombre' => 'admin'],
            [
                'user_apellido' => 'Admin',
                'user_gmail' => 'admin@example.com',
                'user_password' => Hash::make('admin123'),
                'user_tipo' => '0', // admin
                'user_activo' => true,
            ]
        );
    }
}
