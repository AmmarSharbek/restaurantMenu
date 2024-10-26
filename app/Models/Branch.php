<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'restaurant_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'address_ar',
        'address_en',
        'phone',
        'mobile',
        'QR',
        'image_offer',
        'image_common',
        'image_new',
        'num',
        'num_visit',
    ];

protected $casts = [
        'restaurant_id' => 'integer',
        'num' => 'integer',
        'num_visit' => 'integer',
    ];
    /**
     * Get the user that owns the Branch
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'id', 'restaurant_id');
    }

    /**
     * Get all of the menu for the Branch
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menu(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}
