<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\{HasDatabase, HasDomains};
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase;
    use HasDomains;
    use HasUuids;

    protected $fillable = [
        'name',
        'fantasy_name',
        'cnpj',
        'phone',
        'email',
        'postal_code',
        'city',
        'neighborhood',
        'street',
        'number',
        'complement',
        'state',
        'data',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts()
    {
        return [
            'data' => 'array',
        ];
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
