<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'logo',
        'default_image',
        'currency',
        'domin',
        'isActive',
    ];

protected $casts = [
        'isActive' => 'boolean',
    ];
    /**
     * Get all of the comments for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branch(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get the style associated with the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function style(): HasOne
    {
        return $this->hasOne(Style::class);
    }

    /**
     * Get all of the socialmedia for the Restaurant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialmedia(): HasMany
    {
        return $this->hasMany(SocialMedia::class);
    }

    public function userTenant(): HasMany
    {
        return $this->hasMany(UserTenant::class);
    }
}
