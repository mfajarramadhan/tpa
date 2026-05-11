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
            'phone' => '089533353099',
            'email' => 'reza@gmail.com',
            'password' => Hash::make('reza12345'),
            'status' => 'aktif',
            'approval_status' => 'approved',
            'address' => 'Karawang'
        ]);

        // assign role orang tua
        $user->assignRole('orang_tua');


        $teachers = [

            [
                'name' => 'Mariati',
                'email' => 'mariati@gmail.com',
                'password' => 'mariatin12345',
            ],

            [
                'name' => 'Dunipah',
                'email' => 'dunipah@gmail.com',
                'password' => 'dunipah12345',
            ],

            [
                'name' => 'Didah',
                'email' => 'didah@gmail.com',
                'password' => 'didah12345',
            ],

            [
                'name' => 'Rohana',
                'email' => 'rohana@gmail.com',
                'password' => 'rohana12345',
            ],

            [
                'name' => 'Rosi Pratiwi',
                'email' => 'rosipratiwi@gmail.com',
                'password' => 'rosipratiwi12345',
            ],

        ];

        foreach ($teachers as $teacher) {

            $user = User::create([

                'name' => $teacher['name'],
                'phone' => '089533353099',
                'email' => $teacher['email'],
                'password' => Hash::make($teacher['password']),
                'status' => 'aktif',
                'approval_status' => 'approved',
                'address' => 'Karawang',

            ]);

            // assign role guru
            $user->assignRole('guru');

        }
    }
}