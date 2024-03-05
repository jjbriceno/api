<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'phone', 'address', 'profile_picture_id'
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function profilePicture(){
        return $this->belongsTo(ProfilePicture::class);
    }

    public function scopeSearch($query)
    {
        $search = request('search');

        $query->when($search, function ($query) use ($search) {
            $query->where('first_name', 'ilike', $search . '%')
                ->orWhere('last_name', 'ilike', $search . '%')
                ->orWhere('phone', 'ilike', $search . '%')
                ->orWhere('email', 'ilike', $search . '%');
        });

        $query->orderBy("first_name", "asc");

        return $query;
    }

    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = Str::title($value);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = Str::title($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = Str::title($value);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = Str::title($value);
    }
}
