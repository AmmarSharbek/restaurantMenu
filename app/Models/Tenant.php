<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $fillable = [
        'id',
        'data'
    ];


    public function tenantProfile(): HasOne
    {
        return $this->hasOne(TenantProfile::class);
    }
    public function userTenant(): HasMany
    {
        return $this->hasMany(UserTenant::class);
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all of the domin for the Tenant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function domin(): HasMany
    {
        return $this->hasMany(Domain::class);
    }
}
