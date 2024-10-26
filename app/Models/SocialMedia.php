<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialMedia extends Model
{
    use HasFactory;
    protected $fillable = [
        'restaurant_id',
        'name',
        'type',
        'value',
    ];

protected $casts = [
        'restaurant_id' => 'integer',
    ];
    /**
     * Get the restaurant that owns the SocialMedia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'id', 'restaurant_id');
    }
}
