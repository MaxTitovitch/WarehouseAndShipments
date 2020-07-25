<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model {
    protected $fillable = [
        'shipped', 'received', 'shipping_company', 'tracking_number', 'comment', 'quantity', 'user_id'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function products() {
        return $this->belongsToMany('App\Product')->withPivot('quantity');
    }
}
