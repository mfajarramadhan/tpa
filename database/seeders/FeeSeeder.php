<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fee;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fee::firstOrCreate([
            'registration_fee' => 500000,
            'monthly_fee' => 100000,
            'bank_name' => 'BCA',
            'account_name' => 'YAYASAN AL-BAROKAH',
            'account_number' => '1234567890'
        ]);
    }
}
