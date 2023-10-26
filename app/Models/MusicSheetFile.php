<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusicSheetFile extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['file_name', 'file_format', 'binary_file'];

    /**
     * Retrieve the musicSheet associated with the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function musicSheet()
    {
        return $this->hasOne(MusicSheet::class);
    }
}
