<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'name' => 'Swevel Media',
            'alias' => 'Swevel',
            'logo' => 'logo.png',
            'description' => 'lorem ipsum dolor sit amet',
            'phone' => '6281385931773',
            'address' => 'Indonesia',
            'open_date' => '2024-11-01',
            'close_date' => '2025-01-01',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
