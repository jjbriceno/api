<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePicture extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name', 'file_format', 'binary_file'
    ];

    public function profile() {
        return $this->hasOne(Profile::class);
    }
}
