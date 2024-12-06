<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    protected $guarded = ['id'];

    protected $with = ['course'];

    /**
     * Get the course that owns the Meeting
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get all of the schedules for the Meeting
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(MeetingSchedule::class, 'meeting_id');
    }
}
