<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::create([
            'email' => 'author@gmail.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $author->assignRole('author');

        for ($i = 1; $i < 10; $i++) {
            $instructor = User::create([
                'email' => 'mentor' . $i . '@gmail.com',
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $instructor->assignRole('instructor');
        }

        for ($i = 1; $i < 10; $i++) {
            $participant = User::create([
                'email' => 'participant' . $i . '@gmail.com',
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $participant->assignRole('participant');
        }
    }
}
