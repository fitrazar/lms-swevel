<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 11; $i < 20; $i++) {
            DB::table('participants')->insert([
                'user_id' => $i,
                'name' => fake()->unique()->firstName() . ' ' . fake()->unique()->lastName(),
                'gender' => fake()->randomElement(array('Laki - Laki', 'Perempuan')),
                'phone' => '628' . mt_rand(1000000000, 9999999999),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
