<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'user';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' =>$this->profile?->first_name . ' ' . $this->profile?->last_name,
            'first_name' => $this->profile?->first_name,
            'last_name' => $this->profile?->last_name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'profile_picture_url' => config('app.url') . '/storage/profile-pictures/' . $this->profile?->profilePicture?->file_name,
            'roles' => $this->whenLoaded('roles'),
            'permissions' => $this->whenLoaded('roles.permissions'),
        ];
    }

    
}
