<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use SoftDeletes;

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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function schedules(): HasOne
    {
        return $this->hasOne(MeetingSchedule::class, 'meeting_id');
    }
}
