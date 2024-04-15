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
    protected $dates = ['delivery_date', 'loan_date'];

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
    protected $fillable = [
        'user_id',
        'status',
        'loan_date',
        'delivery_date',
        'quantity',
        'type'
    ];

    /**
     * 
     */
    public function musicSheets() {
        return $this->belongsToMany(MusicSheet::class)->withPivot(['music_sheet_id', 'quantity']);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
            $query->whereHas('user', function ($query) use ($search) {
                $query->whereHas('profile', function ($query) use ($search) {
                    $query->where('first_name', 'ilike', '%' . $search . '%')
                        ->orWhere('last_name', 'ilike', '%' . $search . '%');
                });
            });
        });

        $query->orderBy("id", "asc");

        return $query;
    }
}
