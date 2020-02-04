<?php

namespace App\Listeners;

use App\Events\ResourceBanEvent;
use App\Mail\ResourceBanMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class ResourceBanListner
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
    public function handle(ResourceBanEvent $event)
    {
        Mail::to($event->mailData['user']->email)->send(new ResourceBanMail($event->mailData));
    }
}
