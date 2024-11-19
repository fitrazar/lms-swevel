<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    protected $guarded = ['id'];

    protected $with = ['topic'];

    /**
     * Get the topic that owns the Material
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    /**
     * Get the quiz associated with the Material
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class, 'material_id');
    }

    /**
     * Get the assignment associated with the Material
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function assignment(): HasOne
    {
        return $this->hasOne(Assignment::class, 'material_id');
    }
}
