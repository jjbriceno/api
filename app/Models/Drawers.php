<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drawers extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'cabinets_cuantity'
    ];

    public function locations()
    {
        return $this->hasMany(
            Locations::class,
            'drawer_id',
            'id'
        );
    }
}
