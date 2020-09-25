<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderResetMail extends MailMessage
{
    use Queueable, SerializesModels;

    private $url;

    private $user;

    private $order;

    public $markdown = 'user-mails.order';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $user, $order)
    {
        $this->viewData['url'] = $url;
        $this->viewData['user'] = $user;
        $this->viewData['order'] = $order;
    }

}
