<?php

namespace App\Http\Resources\Loan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LoanCollection extends ResourceCollection
{
     /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'loans';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return LoanResource::collection($this->collection);
    }
}
