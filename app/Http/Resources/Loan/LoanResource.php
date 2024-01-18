<?php

namespace App\Http\Resources\Loan;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'borrower_id' => $this->borrower_id,
            'status' => $this->status,
            'loan_date' => $this->loan_date,
            'delivery_date' => $this->delivery_date,
            'music_sheets_borrowed_amount' => $this->music_sheets_borrowed_amount,
            'cuantity' => $this->cuantity,
        ];
    }
}
