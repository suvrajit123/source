<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
{
    protected $contactdata;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\Illuminate\Http\Request $request)
    {
        $this->contactdata = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->contactdata->email)
                    ->subject($this->contactdata->subject)
                    ->with([
                        'message'   =>  $this->contactdata->message,
                        'fullname'  =>  $this->contactdata->first_name.' '.$this->contactdata->last_name
                    ])
                    ->markdown('emails.contactus');
    }
}
