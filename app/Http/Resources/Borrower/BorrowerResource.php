<?php

namespace App\Http\Resources\Borrower;

use App\Http\Resources\Loan\LoanResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowerResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'borrower';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "phone" => $this->phone,
            "email" => $this->email,
            "address" => $this->address,
            "full_name" => $this->first_name . " " . $this->last_name,
            "loans" => LoanResource::collection($this->whenLoaded('loans')),
        ];
    }
}
