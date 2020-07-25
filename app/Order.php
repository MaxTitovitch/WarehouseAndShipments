<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable =[
       'customer',
        'comment',
        'status',
        'shipping_cost',
        'tracking_number',
        'shipped',
        'packing_selection',
        'address',
        'city',
        'zip_postal_code',
        'state_region',
        'country',
        'phone',
        'shipping_company',
        'user_id'
    ];

    public function products() {
        return $this->belongsToMany('App\Product')->withPivot('quantity', 'price', 'description');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
