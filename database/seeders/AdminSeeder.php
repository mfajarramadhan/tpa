<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            [
                'email' => 'fajar@gmail.com',
                'phone' => '0895333530959',
                'name' => 'Muhammad Fajar Ramadhan',
                'address' => 'Karawang',
                'password' => Hash::make('fajar12345'),
                'approval_status' => 'approved',
                'status' => 'aktif',
                'role' => 'superadmin',
            ]
        );

        $admin->assignRole('superadmin');
    }
}
