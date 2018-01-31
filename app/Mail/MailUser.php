<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $address = 'presensi.beraukab@gmail.com';
      $name = 'Presensi Beraukab'; 
      $subject = 'Laravel Email';
 
      return $this->view('mail.mailuser')->from($address, $name)->subject($subject);
    }
}
