<?php

namespace App\Listeners;

use App\Events\UserActivationEvent;
use App\Mail\UserActivationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class UserActivationListner
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
     * @param  UserRegisteredEvent  $event
     * @return void
     */
    public function handle(UserActivationEvent $event)
    {
        Mail::to($event->user->email)->send(new UserActivationMail($event->user));
    }
}
