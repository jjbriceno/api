<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loans extends Model
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
     * Accesor to get author name for this loan
     *
     * @return void
     */
    public function getLoanInfoAttribute()
    {
        $loan = json_decode($this->music_sheets_borrowed_amount, true);

        $musicSheetId = array_keys($loan)[0];

        $musicSheet = MusicSheet::find($musicSheetId);

        return ['author' => $musicSheet->author->full_name, 'title' => $musicSheet->title];
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function borrower()
    {
        return $this->belongsTo(Borrowers::class);
    }
}
