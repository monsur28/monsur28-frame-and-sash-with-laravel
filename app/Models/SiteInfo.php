<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteInfo extends Model
{
    use HasFactory;

    // Fillable properties to allow mass assignment
    protected $fillable = [
        'siteTitle',
        'short_description',
        'copy_right',
        'address',
        'map_link',
        'email',
        'phone',
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
