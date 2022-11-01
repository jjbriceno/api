<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusicSheet extends Model
{

    use HasFactory, SoftDeletes;

    protected $with = ['author', 'gender', 'location'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['author_id', 'gender_id', 'location_id', 'title', 'cuantity'];

    /**
     * Attributes to be hidden from arrays
     *
     * @var array
     */
    protected $hidden = ['author_id', 'gender_id', 'location_id'];

    /**
     * One Music sheets belongs to one author
     *
     * @return void
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * One Music sheets belongs to one gender
     *
     * @return void
     */
    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function location()
    {
        return $this->belongsTo(Locations::class);
    }
}
