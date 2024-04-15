<?php

namespace App\Http\Resources\Calendar;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->first_name . ' ' . $this->last_name,
            'start' => $this->delivery_date->format('Y-m-d\T00:00:00'),
            'end' => $this->delivery_date->format('Y-m-d\T23:59:59'),
            'color' => 'green',
            'timed' => false,
            'details' => $this->musicSheets->map(function ($musicSheet) {
                return [
                    'title' => $musicSheet->title,
                    'author' => $musicSheet->author->full_name,
                    'quantity' => $musicSheet->pivot->quantity
                ];
            })
        ];
    }
}
