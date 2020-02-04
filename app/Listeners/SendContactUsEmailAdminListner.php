<?php

namespace App\Listeners;

use App\Events\ContactUsEvent;
use App\Mail\ContactUsMailClass;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;


class SendContactUsEmailAdminListner
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
     * @param  ContactUsEvent  $event
     * @return void
     */
    public function handle(ContactUsEvent $event)
    {
        Mail::to("admin@islamicresourcehub.com")->send(new ContactUsMailClass($event->ContactData));
    }
}
