<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'title' => 'Web Developer',
            'slug' => 'web-developer',
            'description' => collect(fake()->paragraphs(mt_rand(5, 10)))
                ->map(fn($p) => "<p>$p</p>")
                ->implode(''),
            'excerpt' => fake()->paragraph(),
            'duration' => '200 Menit',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
