<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'balance'
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
}
