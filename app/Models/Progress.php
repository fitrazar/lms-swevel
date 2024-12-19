<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Progress extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['topic', 'participant'];

    /**
     * Get the topic that owns the Progress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    /**
     * Get the participant that owns the Progress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }
}
