<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['quiz'];

    /**
     * Get the quiz that owns the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, foreignKey: 'quiz_id');
    }

    /**
     * Get the Option associated with the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class, 'question_id');
    }

    /**
     * Get the QuestionAnswers associated with the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questionAnswers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class, 'question_id');
    }
}
