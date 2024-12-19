<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionAnswer extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['question', 'quizAttempt'];

    /**
     * Get the question that owns the QuestionAnswer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, foreignKey: 'question_id');
    }

    /**
     * Get the quizAttempt that owns the QuestionAnswer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, foreignKey: 'quiz_attempt_id');
    }

    /**
     * Get the selectedOption that owns the QuestionAnswer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(Option::class, foreignKey: 'selected_option');
    }
}
