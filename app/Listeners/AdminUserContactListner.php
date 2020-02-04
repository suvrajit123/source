<?php

namespace App\Listeners;

use App\Events\AdminUserContactEvent;
use App\Mail\AdminUserContactMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class AdminUserContactListner
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
    public function handle(AdminUserContactEvent $event)
    {
        Mail::to($event->mailData['user']->email)->send(new AdminUserContactMail($event->mailData));
    }
}
