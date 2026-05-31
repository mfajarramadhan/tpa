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
            'phone' => '0895333530955',
            'email' => 'reza@gmail.com',
            'password' => Hash::make('reza12345'),
            'status' => 'aktif',
            'approval_status' => 'approved',
            'address' => 'Karawang',
            'role' => 'orang_tua',
        ]);

        // assign role orang tua
        $user->assignRole('orang_tua');


        $teachers = [
            [
                'name' => 'Sarah Kartini',
                'email' => 'sarah@gmail.com',
                'phone' => '0895333530956',
                'password' => 'sarah12345',
            ],

            [
                'name' => 'Mariati',
                'email' => 'mariati@gmail.com',
                'phone' => '0895333530950',
                'password' => 'mariati12345',
            ],

            [
                'name' => 'Dunipah',
                'email' => 'dunipah@gmail.com',
                'phone' => '0895333530951',
                'password' => 'dunipah12345',
            ],

            [
                'name' => 'Didah',
                'email' => 'didah@gmail.com',
                'phone' => '0895333530952',
                'password' => 'didah12345',
            ],

            [
                'name' => 'Rohana',
                'email' => 'rohana@gmail.com',
                'phone' => '0895333530953',
                'password' => 'rohana12345',
            ],

            [
                'name' => 'Rosi Pratiwi',
                'email' => 'rosipratiwi@gmail.com',
                'phone' => '0895333530954',
                'password' => 'rosipratiwi12345',
            ],

        ];

        foreach ($teachers as $teacher) {

            $user = User::create([

                'name' => $teacher['name'],
                'phone' => $teacher['phone'],
                'email' => $teacher['email'],
                'password' => Hash::make($teacher['password']),
                'status' => 'aktif',
                'approval_status' => 'approved',
                'address' => 'Karawang',
                'role' => 'guru',

            ]);

            // assign role guru
            $user->assignRole('guru');

        }
    }
}