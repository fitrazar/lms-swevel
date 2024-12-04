<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 2; $i < 11; $i++) {
            DB::table('instructors')->insert([
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
