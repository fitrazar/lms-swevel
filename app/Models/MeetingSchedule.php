<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingSchedule extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['meeting'];

    /**
     * Get the meeting that owns the MeetingSchedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }
}
