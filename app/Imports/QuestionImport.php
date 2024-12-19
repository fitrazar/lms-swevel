<?php

namespace App\Imports;

use App\Models\Quiz;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $id = Quiz::where('id', $row['id'])->first()->id;

        $questionText = $row['pertanyaan'];
        $parts = explode('|', $row['pg']);
        $options = array_map(function ($part) {
            return trim(preg_replace('/^[A-D]\./', '', $part));
        }, $parts);

        $correctAnswer = $row['jawaban'];

        $question = Question::create([
            'quiz_id' => $id,
            'question_text' => $questionText,
        ]);
        $correctIndex = ord($correctAnswer) - 65;

        foreach ($options as $index => $pg) {
            $isCorrect = ($index === $correctIndex) ? 1 : 0;
            // dd($index, $isCorrect);

            Option::create([
                'question_id' => $question->id,
                'option_text' => $pg,
                'is_correct' => $isCorrect,
            ]);
        }

        return $question;
    }



    public function headingRow(): int
    {
        return 1;
    }
}
