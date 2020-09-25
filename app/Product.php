<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'brand', 'upc',  'sku', 'received', 'available', 'in_transit', 'user_id'
    ];

    public function shipments() {
        return $this->belongsToMany('App\Shipment')->withPivot('quantity');
    }

    public function orders() {
        return $this->belongsToMany('App\Order')->withPivot('quantity', 'price', 'description');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
