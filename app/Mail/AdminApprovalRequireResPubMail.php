<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Auth;

class AdminApprovalRequireResPubMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resource;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($resource, $user)
    {
        $this->resource = $resource;
        $this->user = $user;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailFooter = DB::table('mail_settings')->where('name', '=', 'footer')->first();
        return $this->from('info@projexin.com', "New Resource Submited")
                    ->subject("New Resource Submited")
                    ->view('emails.resApprovalMail', [
                                'resource' => $this->resource, 
                                'mail_footer' => $mailFooter
                            ]);
    }
}
