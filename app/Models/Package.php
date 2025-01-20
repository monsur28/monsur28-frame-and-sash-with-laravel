<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name', 'price', 'duration'];

    public function features()
    {
        return $this->hasMany(Feature::class);
    }
}
