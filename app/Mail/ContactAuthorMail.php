<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAuthorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fromName;
    public $fromEmail;
    public $subject;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\Illuminate\Http\Request $request)
    {
        $this->fromName  = $request->full_name;
        $this->fromEmail = $request->email;
        $this->subject   = $request->subject;
        $this->message   = $request->message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->fromEmail)
                    ->subject($this->subject)
                    ->with([
                        'message'   =>  $this->message,
                        'fullname'  =>  $this->fromName
                    ])
                    ->markdown('emails.contactus');
    }
}
