<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrowers extends Model
{
    use HasFactory, SoftDeletes;

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
}
