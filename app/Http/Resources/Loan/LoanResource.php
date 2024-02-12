<?php

namespace App\Http\Resources\Loan;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'loan';
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
            'loan_date' => $this->loan_date->locale('es_ES')->isoFormat('LL'),
            'delivery_date' => $this->delivery_date->locale('es_ES')->isoFormat('LL'),
            'cuantity' => $this->cuantity,
            'loan_info' => $this->getLoanInfoAttribute(),
            'music_sheets_ids' => $this->musicSheets()->pluck('music_sheet_id')->toArray(),
        ];
    }
}
