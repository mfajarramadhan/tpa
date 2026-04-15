<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Reza',
            'email' => 'reza@gmail.com',
            'password' => Hash::make('reza12345'),
            'status' => 'aktif',
            'approval_status' => 'approved',
            'address' => 'Karawang'
        ]);

        // assign role orang tua
        $user->assignRole('orang_tua');
    }
}