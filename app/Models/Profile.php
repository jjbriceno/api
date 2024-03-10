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

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = Str::title($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = Str::title($value);
    }

    /**
     * Mutator to format and store the phone number.
     *
     * @param  string  $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        // Remove any formatting characters from the phone number
        // e.g., convert (123) 456-7890 to 1234567890
        $formattedPhoneNumber = preg_replace('/[^0-9]/', '', $value);

        // Format the phone number as 0000 - 000 00 00
        $this->attributes['phone'] = preg_replace('/^(\d{4})(\d{3})(\d{2})(\d{2})$/', '($1) $2 $3 $4', $formattedPhoneNumber);
    }
}
