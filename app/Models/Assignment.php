<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['material'];

    /**
     * Get the material that owns the Assignment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    /**
     * Get all of the results for the Assignment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'assignment_id');
    }
}
