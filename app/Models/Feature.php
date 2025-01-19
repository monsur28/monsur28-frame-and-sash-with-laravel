<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ['package_id', 'name', 'available'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
