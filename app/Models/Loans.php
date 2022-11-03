<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loans extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
            'lender_id',
            'status',
            'loan_date',
            'delivery_date',
            'music_sheets_borrowed_amount',
            'cuantity',
            'status',
            'loan_date',
            'delivery_date',
            'music_sheets_borrowed_amount',
            'cuantity' 
    ];
}
