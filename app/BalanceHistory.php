<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalanceHistory extends Model
{
    protected $fillable = [
        'current_balance', 'transaction_cost', 'type', 'user_id', 'comment'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function save(array $options = [])
    {
        $answer =  parent::save($options);
        if ($this->type == 'Credit') {
            $this->user->sendBalanceNotification($this);
        }
        return $answer;
    }


}
