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

    protected $hidden = ['created_at', 'updated_at', 'cabinet_id', 'drawer_id'];

    protected $appends = ["cabinet_name", "drawer_name"];

    public function drawer()
    {
        return $this->belongsTo(
            Drawers::class,
            'id',
            'drawer_id'
        );
    }
    public function cabin()
    {
        return $this->belongsTo(
            Cabinets::class,
            'id',
            'cabinet_id'
        );
    }

    public function sheets()
    {
        return $this->hasMany(MusicSheet::class);
    }

    public function getCabinetNameAttribute()
    {
        $gabinet = Cabinets::where('id', $this->cabinet_id)->first();

        return $gabinet->name;
    }

    public function getDrawerNameAttribute()
    {
        $gabinet = Drawers::where('id', $this->drawer_id)->first();

        return $gabinet->name;
    }
}
