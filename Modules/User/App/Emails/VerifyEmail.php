<?php

namespace Modules\User\App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationLink;

    public function __construct($verificationLink)
    {
        $this->verificationLink = $verificationLink;
    }

    public function build()
    {
        return $this->view('user::emails.verify-email')
                    ->subject('Verify Your Email Address');
    }
}

