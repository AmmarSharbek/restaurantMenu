<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'idTenant',
        'nameTenant',
        'numTenant'
    ];

    /**
     * Get the tenant that owns the TenantProfile
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'id', 'idTenant');
    }
}
