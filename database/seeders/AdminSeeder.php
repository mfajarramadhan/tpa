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
            ['email' => 'fajar@gmail.com'],
            [
                'name' => 'Fajar',
                'password' => Hash::make('fajar12345'),
                'status' => 'approved'
            ]
        );

        $admin->assignRole('superadmin');
    }
}
