<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Reseller extends Authenticatable implements JWTSubject
{
    use HasFactory;

    // Fillable properties to allow mass assignment
    protected $fillable = [
        'user_id',
        'user_name',
        'first_name',
        'last_name',
        'user_email',
        'mobile_number',
        'country_region',
        'language',
        'address',
        'city',
        'state',
        'zip_code',
        'company_name',
        'company_image',
        'nid',
        'company_email',
        'approved',
        'password',
        'roles',
    ];

    // Hidden properties for arrays
    protected $hidden = [
        'password',
    ];

    // Cast properties to specific types
    protected $casts = [
        'approved' => 'boolean',
    ];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
