<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'option_id',
        'value',
    ];

    /**
     * Get the user that owns the SubOption
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'id', 'option_id');
    }
}
