<?php

namespace App\Http\Resources\Borrower;

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
            "full_name" => $this->profile->first_name . " " . $this->profile->last_name,
            "loans_count" => $this->loans->where('status', 'open')->count() ?? 0,
            "total_music_sheets" => $this->loans->where('status', 'open')->pluck('quantity')->sum()
        ];
    }
}
