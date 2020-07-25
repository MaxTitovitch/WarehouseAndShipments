<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'brand', 'upc',  'sku', 'received', 'Available', 'in_transit'
    ];

    public function shipments() {
        return $this->belongsToMany('App\Shipment')->withPivot('quantity');
    }

    public function orders() {
        return $this->belongsToMany('App\Order')->withPivot('quantity', 'price', 'description');
    }
}
