<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function result(): HasOne
    {
        return $this->hasOne(Result::class, 'participant_id');
    }

    /**
     * Get the submission associated with the participant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function submission(): HasOne
    {
        return $this->hasOne(Submission::class, 'participant_id');
    }

    /**
     * Get the quiz associated with the participant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function quiz(): HasOne
    {
        return $this->hasOne(Result::class, 'participant_id');
    }
}
