<?php

namespace App\Notifications;

use App\Mail\OrderResetMail;
use App\Mail\ShipmentResetMail;
use App\Mail\UserResetMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class ShipmentNotification extends Notification
{
    use Queueable;

    private $shipment;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($shipment)
    {
        $this->shipment = $shipment;
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
        $url = route('inbound-shipments');

        return (new ShipmentResetMail($url, $notifiable, $this->shipment));
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
