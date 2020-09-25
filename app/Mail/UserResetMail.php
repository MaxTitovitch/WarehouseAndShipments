<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UserResetMail extends MailMessage
{
    use Queueable, SerializesModels;

    private $url;

    private $user;

    public $markdown = 'user-mails.reset';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $user)
    {
        $this->viewData['url'] = $url;
        $this->viewData['user'] = $user;
    }

}
