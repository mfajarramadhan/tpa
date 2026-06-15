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
                'email' => 'puspita@gmail.com',
                'phone' => '0822586673921',
                'name' => 'Puspita Winarti',
                'address' => 'Perum Palumbonsari Blok A1 No. 10, Karawang',
                'password' => Hash::make('puspita12345'),
                'approval_status' => 'approved',
                'status' => 'aktif',
                'role' => 'superadmin',
            ]
        );

        $admin->assignRole('superadmin');
    }
}
