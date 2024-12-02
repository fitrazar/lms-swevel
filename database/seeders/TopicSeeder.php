<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Topic::create([
            'course_id' => 1,
            'title' => 'Pendahuluan',
            'slug' => 'pendahuluan',
            'order' => 1,
        ]);
        Topic::create([
            'course_id' => 1,
            'title' => 'Tugas',
            'slug' => 'tugas',
            'order' => 2,
        ]);
        Topic::create([
            'course_id' => 1,
            'title' => 'Kuis',
            'slug' => 'kuis',
            'order' => 3,
        ]);
    }
}
