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
        'primary',
        'onPrimary',
        'secondary',
        'onSecondary',
        'enable',
        'disable',
        'background',
        'onBackground',
    ];
    
    protected $casts = [
        'restaurant_id' => 'integer',
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
