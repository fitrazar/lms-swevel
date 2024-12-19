<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'course_instructors', 'course_id', 'instructor_id')
            ->withTimestamps();
    }

    /**
     * Get all of the topics for the Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class, 'course_id');
    }

    /**
     * Get all of the meetings for the Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class, 'course_id');
    }

    /**
     * Get all of the enrolls for the Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrolls(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
