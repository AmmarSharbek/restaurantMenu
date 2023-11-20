<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Style extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'restaurant_id',
        'primary_font_color',
        'secondary_font_color',
        'background_color',
        'shadow_color',
        'primary_category_color',
        'secondary_category_color',
        'price_color',
        'price_offer_color',
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
}
