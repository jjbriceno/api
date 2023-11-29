<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locations extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cabinet_id', 'drawer_id'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $appends = ["cabinet_name", "drawer_name"];

    public function drawer()
    {
        return $this->belongsTo(Drawers::class,);
    }
    public function cabinet()
    {
        return $this->belongsTo(Cabinets::class);
    }

    public function sheets()
    {
        return $this->hasMany(MusicSheet::class);
    }

    public function getCabinetNameAttribute()
    {
        $cabinet = Cabinets::where('id', $this->cabinet_id)->first();

        return $cabinet->name;
    }

    public function getDrawerNameAttribute()
    {
        $drawer = Drawers::where('id', $this->drawer_id)->first();

        return $drawer->name;
    }
}
