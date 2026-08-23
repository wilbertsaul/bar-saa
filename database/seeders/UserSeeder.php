<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@bar.com'], [
            'name'     => 'Administrador',
            'password' => Hash::make('admin1234'),
            'rol'      => 'admin',
            'activo'   => true,
        ]);

        User::firstOrCreate(['email' => 'control@bar.com'], [
            'name'     => 'Controlador 1',
            'password' => Hash::make('control1234'),
            'rol'      => 'controlador',
            'activo'   => true,
        ]);
    }
}
