<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\EmailVerification\EmailVerificationNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function sendEmailVerificationNotification()
    {
        // We override the default notification and will use our own
        $this->notify(new EmailVerificationNotification());
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::title($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = Str::lower($value);
    }

    public function scopeFiltered($query)
    {
        $query->select('*');

        $query->orderBy("id", "asc");

        return $query;
    }

    public function scopeSearch($query)
    {
        $search = request('search');

        $query->when($search, function ($query) use ($search) {
            $query->whereHas('profile', function ($query) use ($search) {
                $query->where('first_name', 'ilike', $search . '%')
                    ->orWhere('last_name', 'ilike', $search . '%');
            })->orWhereHas('roles', function ($query) use ($search) {
                $query->whereIn('name', [Str::lower($search)]);
            })->orWhere('email', 'ilike', $search . '%');
        });

        $query->orderBy("id", "asc");

        return $query;
    }
}
