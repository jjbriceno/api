<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabinets extends Model
{
    use HasFactory;
    protected $fillable = [
        'name' 
    ];
    public function locations()
    {
       return $this->hasMany(
        Locations::class,'cabinet_id','id'
       );
    }
}
