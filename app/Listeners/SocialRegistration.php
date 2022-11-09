<?php

namespace App\Listeners;

use DutchCodingCompany\FilamentSocialite\Events\Registered;

class SocialRegistration
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->socialiteUser->user;
        $user->email_verified_at = now();
        $user->save();
    }
}
