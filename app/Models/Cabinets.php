<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cabinets extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'drawers_cuantity'
    ];

    public function locations()
    {
        return $this->hasMany(Locations::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::title($value);
    }
}
