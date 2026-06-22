<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject('Your Pharmacy Portal Verification Code')
                    ->html("<h2>Welcome!</h2><p>Your 6-digit registration verification code is: <strong style='font-size: 24px; color: #059669;'>{$this->code}</strong></p><p>This code expires shortly.</p>");
    }
}