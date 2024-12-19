<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['user'];

    /**
     * Get the user that owns the Participant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the progress associated with the participant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class, 'participant_id');
    }

    /**
     * Get the enrolls associated with the participant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrolls(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'participant_id');
    }

    /**
     * Get the result associated with the participant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'participant_id');
    }

    /**
     * Get the quiz associated with the participant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'participant_id');
    }

    /**
     * Get the reports associated with the participant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'participant_id');
    }
}
