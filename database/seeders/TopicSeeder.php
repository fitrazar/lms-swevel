<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // for ($i = 1; $i <= 3; $i++) {
        //     DB::table('topics')->insert([
        //         'course_id' => $i,
        //         'title' => 'Pendahuluan',
        //         'slug' => 'pendahuluan',
        //         'order' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
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

        Topic::create([
            'course_id' => 2,
            'title' => 'Pendahuluan',
            'slug' => 'pendahuluan-2',
            'order' => 1,
        ]);
        Topic::create([
            'course_id' => 2,
            'title' => 'Tugas',
            'slug' => 'tugas-2',
            'order' => 2,
        ]);
        Topic::create([
            'course_id' => 2,
            'title' => 'Kuis',
            'slug' => 'kuis-2',
            'order' => 3,
        ]);
        Topic::create([
            'course_id' => 2,
            'title' => 'Penutup',
            'slug' => 'penutup-2',
            'order' => 4,
        ]);
    }
}
