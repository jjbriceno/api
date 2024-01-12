<?php

namespace App\Http\Resources\Borrower;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BorrowerCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'borrowers';
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return BorrowerResource::collection($this->collection);
    }
}
