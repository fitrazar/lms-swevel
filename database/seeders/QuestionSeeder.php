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

            [
                'question_text' => 'Apa fungsi dari CSS dalam pengembangan web?',
                'options' => [
                    ['option_text' => 'Untuk memberikan gaya pada halaman web', 'is_correct' => 1],
                    ['option_text' => 'Untuk membuat struktur halaman web', 'is_correct' => 0],
                    ['option_text' => 'Untuk mengelola database', 'is_correct' => 0],
                    ['option_text' => 'Untuk melakukan validasi data', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Selector CSS manakah yang digunakan untuk memilih elemen dengan id tertentu?',
                'options' => [
                    ['option_text' => 'id', 'is_correct' => 0],
                    ['option_text' => '#', 'is_correct' => 1],
                    ['option_text' => '.', 'is_correct' => 0],
                    ['option_text' => '*', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Bagaimana cara mengubah warna latar belakang sebuah elemen menggunakan CSS?',
                'options' => [
                    ['option_text' => 'color: blue;', 'is_correct' => 0],
                    ['option_text' => 'background-color: blue;', 'is_correct' => 1],
                    ['option_text' => 'background: red;', 'is_correct' => 0],
                    ['option_text' => 'font-color: blue;', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa arti dari singkatan CSS?',
                'options' => [
                    ['option_text' => 'Colorful Style Sheets', 'is_correct' => 0],
                    ['option_text' => 'Cascading Style Sheets', 'is_correct' => 1],
                    ['option_text' => 'Creative Style Sheets', 'is_correct' => 0],
                    ['option_text' => 'Cascading Simple Sheets', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Properti CSS apa yang digunakan untuk mengatur ukuran font?',
                'options' => [
                    ['option_text' => 'font-size', 'is_correct' => 1],
                    ['option_text' => 'text-size', 'is_correct' => 0],
                    ['option_text' => 'size-font', 'is_correct' => 0],
                    ['option_text' => 'text-font', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Bagaimana cara menambahkan CSS ke dalam HTML?',
                'options' => [
                    ['option_text' => 'Menggunakan tag <style>', 'is_correct' => 1],
                    ['option_text' => 'Menggunakan tag <script>', 'is_correct' => 0],
                    ['option_text' => 'Menggunakan tag <css>', 'is_correct' => 0],
                    ['option_text' => 'Menggunakan tag <link>', 'is_correct' => 1],
                ],
            ],
            [
                'question_text' => 'Properti CSS apa yang digunakan untuk membuat teks menjadi tebal?',
                'options' => [
                    ['option_text' => 'font-weight: bold;', 'is_correct' => 1],
                    ['option_text' => 'text-bold: true;', 'is_correct' => 0],
                    ['option_text' => 'font-bold: yes;', 'is_correct' => 0],
                    ['option_text' => 'font-style: bold;', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa fungsi dari pseudo-class ":hover" dalam CSS?',
                'options' => [
                    ['option_text' => 'Mengubah gaya elemen saat diklik', 'is_correct' => 0],
                    ['option_text' => 'Mengubah gaya elemen saat pointer berada di atasnya', 'is_correct' => 1],
                    ['option_text' => 'Mengubah gaya elemen saat halaman dimuat', 'is_correct' => 0],
                    ['option_text' => 'Mengubah gaya elemen saat dalam keadaan aktif', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Bagaimana cara membuat teks menjadi rata tengah dengan CSS?',
                'options' => [
                    ['option_text' => 'text-align: center;', 'is_correct' => 1],
                    ['option_text' => 'align-text: center;', 'is_correct' => 0],
                    ['option_text' => 'text-center: true;', 'is_correct' => 0],
                    ['option_text' => 'center-align: true;', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa properti CSS yang digunakan untuk mengatur jarak di dalam elemen?',
                'options' => [
                    ['option_text' => 'padding', 'is_correct' => 1],
                    ['option_text' => 'margin', 'is_correct' => 0],
                    ['option_text' => 'spacing', 'is_correct' => 0],
                    ['option_text' => 'border', 'is_correct' => 0],
                ],
            ],

            [
                'question_text' => 'Apa itu PHP?',
                'options' => [
                    ['option_text' => 'Bahasa pemrograman untuk desain grafis', 'is_correct' => 0],
                    ['option_text' => 'Bahasa scripting yang dijalankan di server', 'is_correct' => 1],
                    ['option_text' => 'Framework untuk pengembangan web', 'is_correct' => 0],
                    ['option_text' => 'Bahasa markup untuk membuat halaman web', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa fungsi dari fungsi `echo` dalam PHP?',
                'options' => [
                    ['option_text' => 'Menghapus variabel', 'is_correct' => 0],
                    ['option_text' => 'Menampilkan output ke layar', 'is_correct' => 1],
                    ['option_text' => 'Menyimpan data ke database', 'is_correct' => 0],
                    ['option_text' => 'Menghentikan eksekusi program', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Tag pembuka dan penutup untuk kode PHP adalah?',
                'options' => [
                    ['option_text' => '<?php ?>', 'is_correct' => 1],
                    ['option_text' => '<php></php>', 'is_correct' => 0],
                    ['option_text' => '<% %>', 'is_correct' => 0],
                    ['option_text' => '<script></script>', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Bagaimana cara mendeklarasikan variabel dalam PHP?',
                'options' => [
                    ['option_text' => '$variabel', 'is_correct' => 1],
                    ['option_text' => 'var variabel', 'is_correct' => 0],
                    ['option_text' => 'variabel:', 'is_correct' => 0],
                    ['option_text' => 'let variabel', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Fungsi PHP apa yang digunakan untuk menghubungkan ke database MySQL?',
                'options' => [
                    ['option_text' => 'mysqli_connect', 'is_correct' => 1],
                    ['option_text' => 'mysql_query', 'is_correct' => 0],
                    ['option_text' => 'db_connect', 'is_correct' => 0],
                    ['option_text' => 'connect_mysql', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Metode HTTP apa yang biasanya digunakan untuk mengirim data formulir?',
                'options' => [
                    ['option_text' => 'GET dan POST', 'is_correct' => 1],
                    ['option_text' => 'POST dan PUT', 'is_correct' => 0],
                    ['option_text' => 'DELETE dan PUT', 'is_correct' => 0],
                    ['option_text' => 'GET dan DELETE', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Bagaimana cara memeriksa apakah sebuah variabel telah diset di PHP?',
                'options' => [
                    ['option_text' => 'isset($variabel)', 'is_correct' => 1],
                    ['option_text' => 'check($variabel)', 'is_correct' => 0],
                    ['option_text' => 'is_set($variabel)', 'is_correct' => 0],
                    ['option_text' => 'defined($variabel)', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa output dari kode berikut ini?
        ```php
        $a = "10";
        $b = 10;
        if ($a === $b) {
            echo "Sama";
        } else {
            echo "Tidak Sama";
        }
        ```',
                'options' => [
                    ['option_text' => 'Sama', 'is_correct' => 0],
                    ['option_text' => 'Tidak Sama', 'is_correct' => 1],
                    ['option_text' => 'Error', 'is_correct' => 0],
                    ['option_text' => '10', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Fungsi PHP apa yang digunakan untuk menghitung jumlah elemen dalam sebuah array?',
                'options' => [
                    ['option_text' => 'count()', 'is_correct' => 1],
                    ['option_text' => 'sizeof()', 'is_correct' => 0],
                    ['option_text' => 'length()', 'is_correct' => 0],
                    ['option_text' => 'array_size()', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa hasil dari operasi berikut di PHP?
        ```php
        echo 10 % 3;
        ```',
                'options' => [
                    ['option_text' => '3', 'is_correct' => 0],
                    ['option_text' => '1', 'is_correct' => 1],
                    ['option_text' => '0', 'is_correct' => 0],
                    ['option_text' => 'Error', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Bagaimana cara memulai sesi di PHP?',
                'options' => [
                    ['option_text' => 'session_start();', 'is_correct' => 1],
                    ['option_text' => 'start_session();', 'is_correct' => 0],
                    ['option_text' => 'begin_session();', 'is_correct' => 0],
                    ['option_text' => 'create_session();', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa fungsi dari `include` dalam PHP?',
                'options' => [
                    ['option_text' => 'Untuk menghubungkan PHP dengan database', 'is_correct' => 0],
                    ['option_text' => 'Untuk menyisipkan file PHP lain ke dalam file saat ini', 'is_correct' => 1],
                    ['option_text' => 'Untuk membuat file baru di server', 'is_correct' => 0],
                    ['option_text' => 'Untuk mengeksekusi file eksternal', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Bagaimana cara menangani error di PHP secara manual?',
                'options' => [
                    ['option_text' => 'Menggunakan `try` dan `catch`', 'is_correct' => 1],
                    ['option_text' => 'Menggunakan `if` dan `else`', 'is_correct' => 0],
                    ['option_text' => 'Menggunakan `while` dan `for`', 'is_correct' => 0],
                    ['option_text' => 'Menggunakan `include` dan `require`', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa perbedaan antara `include` dan `require` di PHP?',
                'options' => [
                    ['option_text' => '`require` akan menghentikan eksekusi jika file tidak ditemukan, sedangkan `include` tidak', 'is_correct' => 1],
                    ['option_text' => '`include` lebih cepat daripada `require`', 'is_correct' => 0],
                    ['option_text' => '`require` hanya untuk file eksternal', 'is_correct' => 0],
                    ['option_text' => '`include` digunakan untuk file lokal saja', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa itu Laravel?',
                'options' => [
                    ['option_text' => 'Framework PHP untuk pengembangan web', 'is_correct' => 1],
                    ['option_text' => 'Bahasa pemrograman untuk desain web', 'is_correct' => 0],
                    ['option_text' => 'Sistem manajemen konten', 'is_correct' => 0],
                    ['option_text' => 'Tool untuk desain grafis', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa perintah untuk membuat model di Laravel?',
                'options' => [
                    ['option_text' => 'php artisan make:model', 'is_correct' => 1],
                    ['option_text' => 'php artisan create:model', 'is_correct' => 0],
                    ['option_text' => 'php artisan new:model', 'is_correct' => 0],
                    ['option_text' => 'php artisan model:create', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa fungsi dari `Migration` di Laravel?',
                'options' => [
                    ['option_text' => 'Untuk mengelola versi database', 'is_correct' => 1],
                    ['option_text' => 'Untuk membuat file controller', 'is_correct' => 0],
                    ['option_text' => 'Untuk menjalankan aplikasi', 'is_correct' => 0],
                    ['option_text' => 'Untuk membuat API', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa perintah untuk menjalankan server Laravel?',
                'options' => [
                    ['option_text' => 'php artisan serve', 'is_correct' => 1],
                    ['option_text' => 'php artisan start', 'is_correct' => 0],
                    ['option_text' => 'php artisan server', 'is_correct' => 0],
                    ['option_text' => 'php artisan run', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa fungsi dari `Eloquent` di Laravel?',
                'options' => [
                    ['option_text' => 'ORM untuk memudahkan interaksi dengan database', 'is_correct' => 1],
                    ['option_text' => 'Template engine untuk tampilan', 'is_correct' => 0],
                    ['option_text' => 'Library untuk autentikasi', 'is_correct' => 0],
                    ['option_text' => 'Fungsi untuk routing', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa perintah untuk membuat controller di Laravel?',
                'options' => [
                    ['option_text' => 'php artisan make:controller', 'is_correct' => 1],
                    ['option_text' => 'php artisan create:controller', 'is_correct' => 0],
                    ['option_text' => 'php artisan new:controller', 'is_correct' => 0],
                    ['option_text' => 'php artisan controller:create', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'File konfigurasi database di Laravel terletak di mana?',
                'options' => [
                    ['option_text' => 'config/database.php', 'is_correct' => 1],
                    ['option_text' => 'routes/web.php', 'is_correct' => 0],
                    ['option_text' => 'resources/views', 'is_correct' => 0],
                    ['option_text' => '.env', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa fungsi dari file `.env` di Laravel?',
                'options' => [
                    ['option_text' => 'Menyimpan konfigurasi aplikasi, seperti database dan lingkungan kerja', 'is_correct' => 1],
                    ['option_text' => 'Mengatur routing aplikasi', 'is_correct' => 0],
                    ['option_text' => 'Menampilkan halaman view', 'is_correct' => 0],
                    ['option_text' => 'Membuat model baru', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa yang dilakukan oleh perintah `php artisan migrate`?',
                'options' => [
                    ['option_text' => 'Menjalankan file migration untuk membuat atau mengubah tabel di database', 'is_correct' => 1],
                    ['option_text' => 'Membuat file migration baru', 'is_correct' => 0],
                    ['option_text' => 'Menghapus tabel di database', 'is_correct' => 0],
                    ['option_text' => 'Menjalankan seeders', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa nama template engine yang digunakan di Laravel?',
                'options' => [
                    ['option_text' => 'Blade', 'is_correct' => 1],
                    ['option_text' => 'Twig', 'is_correct' => 0],
                    ['option_text' => 'Smarty', 'is_correct' => 0],
                    ['option_text' => 'Handlebars', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa fungsi dari `Route::get()` di Laravel?',
                'options' => [
                    ['option_text' => 'Mendefinisikan route untuk permintaan HTTP GET', 'is_correct' => 1],
                    ['option_text' => 'Mendefinisikan route untuk permintaan HTTP POST', 'is_correct' => 0],
                    ['option_text' => 'Mendefinisikan middleware pada route', 'is_correct' => 0],
                    ['option_text' => 'Mengirim respon JSON', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa perintah untuk membersihkan cache di Laravel?',
                'options' => [
                    ['option_text' => 'php artisan cache:clear', 'is_correct' => 1],
                    ['option_text' => 'php artisan config:clear', 'is_correct' => 0],
                    ['option_text' => 'php artisan view:clear', 'is_correct' => 0],
                    ['option_text' => 'php artisan route:clear', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa fungsi dari middleware di Laravel?',
                'options' => [
                    ['option_text' => 'Memfilter permintaan HTTP sebelum mencapai controller', 'is_correct' => 1],
                    ['option_text' => 'Menyimpan data ke database', 'is_correct' => 0],
                    ['option_text' => 'Menampilkan halaman error', 'is_correct' => 0],
                    ['option_text' => 'Mengelola routing aplikasi', 'is_correct' => 0],
                ],
            ],
            [
                'question_text' => 'Apa yang dilakukan oleh perintah `php artisan tinker`?',
                'options' => [
                    ['option_text' => 'Membuka CLI interaktif untuk menjalankan perintah PHP di Laravel', 'is_correct' => 1],
                    ['option_text' => 'Menginstal package Laravel', 'is_correct' => 0],
                    ['option_text' => 'Menghapus cache aplikasi', 'is_correct' => 0],
                    ['option_text' => 'Mengupdate aplikasi Laravel', 'is_correct' => 0],
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
    }
}
