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
        $course = Course::create([
            'title' => 'Web Developer',
            'slug' => 'web-developer',
            'description' => collect(fake()->paragraphs(mt_rand(5, 10)))
                ->map(fn($p) => "<p>$p</p>")
                ->implode(''),
            'excerpt' => fake()->paragraph(),
            'duration' => '200',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $course->instructors()->attach(3);

        $course2 = Course::create([
            'title' => 'PHP Laravel',
            'slug' => 'php-laravel',
            'description' => collect(fake()->paragraphs(mt_rand(5, 10)))
                ->map(fn($p) => "<p>$p</p>")
                ->implode(''),
            'excerpt' => fake()->paragraph(),
            'duration' => '200',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $course2->instructors()->attach(4);
    }
}
