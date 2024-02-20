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

    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = Str::title($value);
    }
}
