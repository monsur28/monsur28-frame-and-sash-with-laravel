<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
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
}
