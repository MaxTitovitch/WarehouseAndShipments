<?php

namespace App;

use App\Notifications\BalanceNotification;
use App\Notifications\OrderNotification;
use App\Notifications\ShipmentNotification;
use App\Notifications\UserResetNotification;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'suite', 'email', 'password', 'role', 'balance', 'fee'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shipments() {
        return $this->hasMany('App\Shipment');
    }

    public function orders() {
        return $this->hasMany('App\Order');
    }

    public function balanceHistories() {
        return $this->hasMany('App\BalanceHistory');
    }


    public function products() {
        return $this->hasMany('App\Product');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetNotification($token));
    }

    public function sendOrderNotification($order)
    {
        $this->notify(new OrderNotification($order));
    }

    public function sendShipmentNotification($shipment)
    {
        $this->notify(new ShipmentNotification($shipment));
    }

    public function sendBalanceNotification($balanceHistory)
    {
        $this->notify(new BalanceNotification($balanceHistory));
    }
}
