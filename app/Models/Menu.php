<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'branch_id',
        'name_en',
        'name_ar',
        'image',
        'num_visit',
    ];

protected $casts = [
        'branch_id' => 'integer',
    ];
    /**
     * Get the branch that owns the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'id', 'branch_id');
    }

    /**
     * Get all of the category for the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function category(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
