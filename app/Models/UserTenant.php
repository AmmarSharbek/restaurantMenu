<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserTenant extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        "userNameTenant",
        "userPassTenant",
        "phone",
        "isAdmin",
        "sumPermessions",
        "idRestaurant"
    ];

protected $casts = [
        'isAdmin' => 'boolean',
        'sumPermessions' => 'integer',
        'idRestaurant' => 'integer',
    ];
    protected $hidden = [
        'remember_token',
    ];


    /**
     * Get the tenant that owns the UserTenant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'id', 'idRestaurant');
    }
}
