<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalanceHistory extends Model
{
    protected $fillable = [
        'current_balance', 'transaction_cost', 'type', 'user_id'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
