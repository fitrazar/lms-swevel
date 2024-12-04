<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Material::create([
            'topic_id' => 1,
            'content' => collect(fake()->paragraphs(mt_rand(5, 10)))
                ->map(fn($p) => "<p>$p</p>")
                ->implode(''),
            'type' => 'document',
        ]);
        Material::create([
            'topic_id' => 2,
            'content' => collect(fake()->paragraphs(mt_rand(5, 10)))
                ->map(fn($p) => "<p>$p</p>")
                ->implode(''),
            'type' => 'assignment',
        ]);
        Material::create([
            'topic_id' => 3,
            'content' => collect(fake()->paragraphs(mt_rand(5, 10)))
                ->map(fn($p) => "<p>$p</p>")
                ->implode(''),
            'type' => 'quiz',
        ]);
    }
}
