<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    protected $guarded = ['id'];

    protected $with = ['quiz', 'participant'];

    /**
     * Get the quiz that owns the QuizAttempt
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, foreignKey: 'quiz_id');
    }

    /**
     * Get the participant that owns the QuizAttempt
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class, foreignKey: 'participant_id');
    }

    /**
     * Get all of the QuestionAnswers for the QuizAttempt
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questionAnswers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class, 'quiz_attempt_id');
    }
}
