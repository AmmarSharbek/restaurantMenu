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
        "idTenant"
    ];

    protected $hidden = [
        'userPassTenant',
        'remember_token',
    ];

    protected $casts = [
        'userPassTenant' => 'hashed',
    ];

    /**
     * Get the tenant that owns the UserTenant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'id', 'idTenant');
    }
}
