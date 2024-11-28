<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    protected $guarded = ['id'];

    protected $with = ['course'];

    /**
     * Get the course that owns the Topic
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get one of the material for the Topic
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function material(): HasOne
    {
        return $this->hasOne(Material::class, 'topic_id');
    }

    /**
     * Get all of the progress for the Topic
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class, 'topic_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
