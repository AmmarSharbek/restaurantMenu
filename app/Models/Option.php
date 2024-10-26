<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'name_en',
        'name_ar',
    ];

protected $casts = [
        'product_id' => 'integer',
    ];
    /**
     * Get the product that owns the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id', 'product_id');
    }

    /**
     * Get all of the subOption for the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subOption(): HasMany
    {
        return $this->hasMany(SubOption::class);
    }
}
