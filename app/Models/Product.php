<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'category_id',
        'name_en',
        'name_ar',
        'description_ar',
        'description_en',
        'image',
        'price',
        'price_offer',
        'common',
        'new',
        'hidden',
        'unavailable',
        'sortNum',
        'num_visit'
    ];

protected $casts = [
        'category_id' => 'integer',
        'price' => 'double',
        'price_offer' => 'double',
        'common' => 'boolean',
        'new' => 'boolean',
        'hidden' => 'boolean',
        'unavailable' => 'boolean',
        'num_visit' => 'integer',
    ];
    /**
     * Get the category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id', 'category_id');
    }
    /**
     * Get all of the option for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function option(): HasMany
    {
        return $this->hasMany(Option::class);
    }
}
