<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $casts = ['delivery_date' => 'datetime:d-m-Y', 'loan_date' => 'datetime:d-m-Y'];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $hidden = ['created_at', 'deleted_at', 'updated_at'];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $appends = ['loan_info'];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $fillable = [
        'borrower_id',
        'status',
        'loan_date',
        'delivery_date',
        'music_sheets_borrowed_amount',
        'cuantity'
    ];

    /**
     * 
     */
    public function musicSheets() {
        return $this->belongsToMany(MusicSheet::class);
    }

    /**
     * Accesor to get author name for this loan
     *
     * @return void
     */
    public function getLoanInfoAttribute()
    {
        // TODO: Corregir para procesar n partituras
        $musicSheet = $this->musicSheets()->with("author")->get()[0];

        return ['author' => $musicSheet->author->full_name, 'title' => $musicSheet->title];
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
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
            $query->whereHas('borrower', function ($query) use ($search) {
                $query->where('first_name', 'ilike', $search . '%')
                    ->orWhere('last_name', 'ilike', $search . '%');
            });
        });

        $query->orderBy("id", "asc");

        return $query;
    }
}
