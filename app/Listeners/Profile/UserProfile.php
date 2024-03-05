<?php

namespace App\Listeners\Profile;

use App\Models\Profile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserProfile
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Profile::create([
            'user_id' => $event->user_id,
            'first_name' => $event->profile->firstName,
            'last_name' => $event->profile->lastName,
        ]);
    }
}
