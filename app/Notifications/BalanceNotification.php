<?php

namespace App\Notifications;

use App\Mail\BalanceResetMail;
use App\Mail\OrderResetMail;
use App\Mail\ShipmentResetMail;
use App\Mail\UserResetMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class BalanceNotification extends Notification
{
    use Queueable;

    private $balanceHistory;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($balanceHistory)
    {
        $this->balanceHistory = $balanceHistory;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        $url = route('home');

        return (new BalanceResetMail($url, $notifiable, $this->balanceHistory));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
