<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'cabinet_id', 'drawer_id' 
    ];

    public function drawer()
    {
       return $this->belongsTo(
        Drawers::class,'id','drawer_id'
       );
    }
    public function cabin()
    {
       return $this->belongsTo(
        Cabinets::class,'id','cabinet_id'
       );
    }
}
