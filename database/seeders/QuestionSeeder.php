<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $questions = [
            [
                'question_text' => 'Apa itu HTML?',
                'options' => [
                    ['option_text' => 'Hyper Text Markup Language', 'is_correct' => 1],
                    ['option_text' => 'Hyperlinks and Text Markup Language', 'is_correct' => 0],
                    ['option_text' => 'Home Tool Markup Language', 'is_correct' => 0],
                    ['option_text' => 'Hyper Tool Markup Language', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa fungsi utama dari CSS?',
                'options' => [
                    ['option_text' => 'Membuat database', 'is_correct' => 0],
                    ['option_text' => 'Mengelola tampilan halaman web', 'is_correct' => 1],
                    ['option_text' => 'Mengatur logika program', 'is_correct' => 0],
                    ['option_text' => 'Menyusun struktur halaman web', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu PHP?',
                'options' => [
                    ['option_text' => 'Bahasa scripting untuk pengembangan web', 'is_correct' => 1],
                    ['option_text' => 'Platform hosting', 'is_correct' => 0],
                    ['option_text' => 'Protokol transfer data', 'is_correct' => 0],
                    ['option_text' => 'Sistem manajemen konten', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu Laravel?',
                'options' => [
                    ['option_text' => 'Framework PHP', 'is_correct' => 1],
                    ['option_text' => 'Bahasa pemrograman', 'is_correct' => 0],
                    ['option_text' => 'Framework JavaScript', 'is_correct' => 0],
                    ['option_text' => 'Database management tool', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu REST API?',
                'options' => [
                    ['option_text' => 'Antarmuka untuk berkomunikasi antar aplikasi', 'is_correct' => 1],
                    ['option_text' => 'Bahasa pemrograman baru', 'is_correct' => 0],
                    ['option_text' => 'Alat untuk desain web', 'is_correct' => 0],
                    ['option_text' => 'Framework CSS', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu JavaScript?',
                'options' => [
                    ['option_text' => 'Bahasa untuk membuat interaktivitas pada halaman web', 'is_correct' => 1],
                    ['option_text' => 'Bahasa untuk menyusun struktur web', 'is_correct' => 0],
                    ['option_text' => 'Framework untuk CSS', 'is_correct' => 0],
                    ['option_text' => 'Bahasa untuk manajemen database', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu SQL?',
                'options' => [
                    ['option_text' => 'Bahasa untuk manajemen database', 'is_correct' => 1],
                    ['option_text' => 'Framework PHP', 'is_correct' => 0],
                    ['option_text' => 'Bahasa untuk desain halaman web', 'is_correct' => 0],
                    ['option_text' => 'Bahasa untuk menambahkan efek visual', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu Vue.js?',
                'options' => [
                    ['option_text' => 'Framework JavaScript', 'is_correct' => 1],
                    ['option_text' => 'Framework PHP', 'is_correct' => 0],
                    ['option_text' => 'Framework CSS', 'is_correct' => 0],
                    ['option_text' => 'Alat untuk hosting', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu Node.js?',
                'options' => [
                    ['option_text' => 'Runtime untuk menjalankan JavaScript di server', 'is_correct' => 1],
                    ['option_text' => 'Framework CSS', 'is_correct' => 0],
                    ['option_text' => 'Bahasa pemrograman baru', 'is_correct' => 0],
                    ['option_text' => 'Alat untuk manajemen database', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu Git?',
                'options' => [
                    ['option_text' => 'Sistem kontrol versi', 'is_correct' => 1],
                    ['option_text' => 'Bahasa pemrograman', 'is_correct' => 0],
                    ['option_text' => 'Framework JavaScript', 'is_correct' => 0],
                    ['option_text' => 'Tool untuk desain grafis', 'is_correct' => 0],
                ],
            ],
        ];

        $questions2 = [
            [
                'question_text' => 'Apa itu Python?',
                'options' => [
                    ['option_text' => 'Bahasa pemrograman serbaguna', 'is_correct' => 1],
                    ['option_text' => 'Framework PHP', 'is_correct' => 0],
                    ['option_text' => 'Library JavaScript', 'is_correct' => 0],
                    ['option_text' => 'Database management tool', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu DOM (Document Object Model)?',
                'options' => [
                    ['option_text' => 'Representasi struktur dokumen HTML atau XML', 'is_correct' => 1],
                    ['option_text' => 'Framework CSS', 'is_correct' => 0],
                    ['option_text' => 'Protokol untuk pengiriman data', 'is_correct' => 0],
                    ['option_text' => 'Tool untuk debugging', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu OOP (Object-Oriented Programming)?',
                'options' => [
                    ['option_text' => 'Paradigma pemrograman berbasis objek', 'is_correct' => 1],
                    ['option_text' => 'Bahasa scripting untuk web', 'is_correct' => 0],
                    ['option_text' => 'Sistem manajemen file', 'is_correct' => 0],
                    ['option_text' => 'Protokol untuk API', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu API?',
                'options' => [
                    ['option_text' => 'Antarmuka untuk berkomunikasi antar aplikasi', 'is_correct' => 1],
                    ['option_text' => 'Bahasa pemrograman', 'is_correct' => 0],
                    ['option_text' => 'Framework CSS', 'is_correct' => 0],
                    ['option_text' => 'Database management tool', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu asynchronous programming?',
                'options' => [
                    ['option_text' => 'Metode menjalankan tugas secara non-blocking', 'is_correct' => 1],
                    ['option_text' => 'Paradigma pemrograman berbasis objek', 'is_correct' => 0],
                    ['option_text' => 'Tool untuk debugging', 'is_correct' => 0],
                    ['option_text' => 'Framework untuk pengembangan web', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa fungsi utama dari GitHub?',
                'options' => [
                    ['option_text' => 'Menyimpan dan mengelola kode secara versi', 'is_correct' => 1],
                    ['option_text' => 'Membuat desain UI', 'is_correct' => 0],
                    ['option_text' => 'Mengelola database', 'is_correct' => 0],
                    ['option_text' => 'Menjalankan kode di server', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu Bootstrap?',
                'options' => [
                    ['option_text' => 'Framework untuk membuat UI responsif', 'is_correct' => 1],
                    ['option_text' => 'Bahasa pemrograman', 'is_correct' => 0],
                    ['option_text' => 'Tool untuk debugging kode', 'is_correct' => 0],
                    ['option_text' => 'Framework untuk pengembangan API', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu NoSQL?',
                'options' => [
                    ['option_text' => 'Database non-relasional', 'is_correct' => 1],
                    ['option_text' => 'Bahasa scripting untuk web', 'is_correct' => 0],
                    ['option_text' => 'Framework untuk JavaScript', 'is_correct' => 0],
                    ['option_text' => 'Library untuk Python', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu middleware?',
                'options' => [
                    ['option_text' => 'Komponen yang mengelola request dan response di aplikasi', 'is_correct' => 1],
                    ['option_text' => 'Tool untuk membuat API', 'is_correct' => 0],
                    ['option_text' => 'Database management tool', 'is_correct' => 0],
                    ['option_text' => 'Framework untuk pengembangan web', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu AJAX?',
                'options' => [
                    ['option_text' => 'Teknologi untuk membuat request asynchronous', 'is_correct' => 1],
                    ['option_text' => 'Bahasa pemrograman untuk pengembangan aplikasi', 'is_correct' => 0],
                    ['option_text' => 'Sistem untuk manajemen file', 'is_correct' => 0],
                    ['option_text' => 'Framework untuk pengelolaan UI', 'is_correct' => 0],
                ],
            ],
        ];


        foreach ($questions as $data) {
            $question = Question::create([
                'quiz_id' => 1,
                'question_text' => $data['question_text'],
            ]);

            foreach ($data['options'] as $option) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $option['option_text'],
                    'is_correct' => $option['is_correct'],
                ]);
            }
        }

        foreach ($questions2 as $item) {
            $question = Question::create([
                'quiz_id' => 2,
                'question_text' => $item['question_text'],
            ]);

            foreach ($item['options'] as $option) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $option['option_text'],
                    'is_correct' => $option['is_correct'],
                ]);
            }
        }
    }
}
