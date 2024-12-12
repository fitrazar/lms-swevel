<?php

namespace Database\Seeders;

use App\Models\Assignment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Assignment::create([
            'title' => 'Tugas Akhir',
            'material_id' => 2,
            'description' => collect(fake()->paragraphs(mt_rand(5, 10)))
                ->map(fn($p) => "<p>$p</p>")
                ->implode(''),
            'deadline' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Assignment::create([
            'title' => 'Tugas Akhir',
            'material_id' => 5,
            'description' => collect(fake()->paragraphs(mt_rand(5, 10)))
                ->map(fn($p) => "<p>$p</p>")
                ->implode(''),
            'deadline' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
