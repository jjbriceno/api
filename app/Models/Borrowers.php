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
}
