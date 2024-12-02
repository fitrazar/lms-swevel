<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Quiz::create([
            'title' => 'Kuis ABC',
            'material_id' => 3,
            'description' => collect(fake()->paragraphs(mt_rand(5, 10)))
                ->map(fn($p) => "<p>$p</p>")
                ->implode(''),
            'start_time' => now(),
            'end_time' => now()->addHours(value: 1),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
