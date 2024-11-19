<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
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
    public function option(): HasMany
    {
        return $this->hasMany(Option::class, 'question_id');
    }
}
