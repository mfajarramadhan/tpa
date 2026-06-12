<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [

            [
                'name' => 'Mariati',
                'email' => 'mariati@gmail.com',
                'phone' => '0895333530950',
                'password' => 'mariati12345',
                'address' => 'Perum Palumbonsari Blok A2 No. 11, Karawang',
            ],

            [
                'name' => 'Dunipah',
                'email' => 'dunipah@gmail.com',
                'phone' => '0895333530951',
                'password' => 'dunipah12345',
                'address' => 'Perum Palumbonsari Blok C4 No. 8, Karawang',
            ],

            [
                'name' => 'Didah',
                'email' => 'didah@gmail.com',
                'phone' => '0895333530952',
                'password' => 'didah12345',
                'address' => 'Jl. Lamaran RT 04 RW 06, Palumbonsari',
            ],

            [
                'name' => 'Rohana',
                'email' => 'rohana@gmail.com',
                'phone' => '0895333530953',
                'password' => 'rohana12345',
                'address' => 'Perum Griya Palumbonsari Blok B1 No. 17, Karawang',
            ],

            [
                'name' => 'Rosi Pratiwi',
                'email' => 'rosipratiwi@gmail.com',
                'phone' => '0895333530954',
                'password' => 'rosipratiwi12345',
                'address' => 'Perum Palumbonsari Blok D3 No. 5, Karawang',
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
                'address' => $teacher['address'],
                'role' => 'guru',
            ]);

            // assign role guru
            $user->assignRole('guru');
        }
    }
}