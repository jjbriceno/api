<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array <int, string>
     */
    protected $fillable = ['full_name'];

    public function musicSheets()
    {
        return $this->HasMany(MusicSheet::class);
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
            $query->where('full_name', 'ilike', $search . '%');
        });

        $query->orderBy("full_name", "asc");

        return $query;
    }
}
