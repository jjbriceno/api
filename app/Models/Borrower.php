<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Borrower extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'borrowers';

    protected $fillable = [
        'first_name', 'last_name', 'phone', 'email', 'address'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function loans()
    {
        return $this->hasMany(Loans::class);
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
            $query->where('first_name', 'ilike', $search . '%')
                ->orWhere('last_name', 'ilike', $search . '%')
                ->orWhere('phone', 'ilike', $search . '%')
                ->orWhere('email', 'ilike', $search . '%');
        });

        $query->orderBy("first_name", "asc");

        return $query;
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = Str::title($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = Str::title($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = Str::lower($value);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = Str::title($value);
    }
}
