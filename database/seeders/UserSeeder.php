<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $parents = [

            [
                'name' => 'Sarah Kartini',
                'phone' => '0895629370698',
                'email' => 'sarahkartini@gmail.com',
                'password' => 'sarahkartini12345',
                'address' => 'Perum Palumbonsari Blok C2 No. 15, Karawang',
            ],

            [
                'name' => 'Muhammad Fajar Ramadhan',
                'phone' => '0822586673921',
                'email' => 'muhammadfajar@gmail.com',
                'password' => 'muhammadfajar12345',
                'address' => 'Perum Palumbonsari Blok B5 No. 8, Karawang',
            ],

            [
                'name' => 'Siti Nurjanah',
                'phone' => '081327451892',
                'email' => 'sitinurjanah@gmail.com',
                'password' => 'sitinurjanah12345',
                'address' => 'Perum Griya Asri 2 Blok A1 No. 7, Palumbonsari',
            ],

            [
                'name' => 'Dewi Lestari',
                'phone' => '085723614982',
                'email' => 'dewilestari@gmail.com',
                'password' => 'dewilestari12345',
                'address' => 'Jl. Lamaran RT 03 RW 05, Palumbonsari',
            ],

            [
                'name' => 'Yayah Rohayati',
                'phone' => '081289563471',
                'email' => 'yayahrohayati@gmail.com',
                'password' => 'yayahrohayati12345',
                'address' => 'Perum Palumbonsari Blok D4 No. 21, Karawang',
            ],

            [
                'name' => 'Nur Aisyah',
                'phone' => '085615728349',
                'email' => 'nuraisyah@gmail.com',
                'password' => 'nuraisyah12345',
                'address' => 'Jl. Sukaluyu No. 14, Telukjambe Timur',
            ],

            [
                'name' => 'Rina Marlina',
                'phone' => '082134765981',
                'email' => 'rinamarlina@gmail.com',
                'password' => 'rinamarlina12345',
                'address' => 'Perum Bumi Telukjambe Blok E3 No. 10',
            ],

            [
                'name' => 'Tati Hartati',
                'phone' => '087821456379',
                'email' => 'tatihartati@gmail.com',
                'password' => 'tatihartati12345',
                'address' => 'Perum Palumbonsari Blok A3 No. 6, Karawang',
            ],

            [
                'name' => 'Sulastri',
                'phone' => '081390275648',
                'email' => 'sulastri@gmail.com',
                'password' => 'sulastri12345',
                'address' => 'Jl. Lamaran Gang Melati No. 9, Karawang',
            ],

            [
                'name' => 'Yulianti',
                'phone' => '085298341765',
                'email' => 'yulianti@gmail.com',
                'password' => 'yulianti12345',
                'address' => 'Perum Griya Palumbonsari Blok F2 No. 12',
            ],

        ];

        foreach ($parents as $parent) {

            $user = User::create([

                'name' => $parent['name'],
                'phone' => $parent['phone'],
                'email' => $parent['email'],
                'password' => Hash::make($parent['password']),
                'status' => 'aktif',
                'approval_status' => 'approved',
                'address' => $parent['address'],
                'role' => 'orang_tua',

            ]);

            $user->assignRole('orang_tua');
        }
    }
}