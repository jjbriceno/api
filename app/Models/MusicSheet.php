<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusicSheet extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $with = ['author', 'gender', 'location', 'musicSheetFile'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['author_id', 'gender_id', 'location_id', 'title', 'available', 'music_sheet_file_id'];

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

    /**
     * Undocumented function
     *
     * @return belongsTo
     */
    public function location()
    {
        return $this->belongsTo(Locations::class);
    }

    /**
     * Retrieves the related MusicSheetFile model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function musicSheetFile()
    {
        return $this->belongsTo(MusicSheetFile::class);
    }

    /**
     * Filters the query based on the search criteria and sort order.
     *
     * @param mixed $query The query to be filtered.
     * @return void
     */
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
            $query->where('title', 'ilike', $search . '%')
            ->orWhere(
                function ($query) use ($search) {
                    $query->whereHas('author', function ($query) use ($search) {
                        $query->where('full_name', 'ilike', $search . '%');
                    });
                }
            )->orWhere(
                function ($query) use ($search) {
                    $query->whereHas('gender', function ($query) use ($search) {
                        $query->where('name', 'ilike', $search . '%');
                    });
                }
            )->orWhere(
                function ($query) use ($search) {
                    $query->whereHas('location', function ($query) use ($search) {
                        $query->whereHas('drawer', function ($query) use ($search) {
                            $query->where('name', 'ilike', $search . '%');
                        })->orWhere(
                            function ($query) use ($search) {
                                $query->whereHas('cabinet', function ($query) use ($search) {
                                    $query->where('name', 'ilike', $search . '%');
                                });
                            }
                        );
                    });
                }
            );
            
        });

        $query->orderBy("title", "asc");

        return $query;
    }
}
