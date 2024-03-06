<?php

namespace App\Listeners\Profile;

use App\Models\Profile;

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
            'user_id'       => $event->user->id,
            'first_name'    => $event->user->name,
            'last_name'     => $event->user->last_name,
        ]);
    }
}
