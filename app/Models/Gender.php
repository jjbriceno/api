<?php

namespace App\Models;

use App\Models\MusicSheet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gender extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];


    /**
     * Get the musicSheets that owns the Gender
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function musicSheets()
    {
        return $this->hasMany(MusicSheet::class);
    }
}
