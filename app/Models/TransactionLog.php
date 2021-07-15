<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $fillable = [
        'amount', 
        'email', 
        'gateway_reference', 
        'system_reference', 
        'reason'
        //'source'
    ];

    public function order() {
        return $this->belongsTo(Order::class, 'system_reference', 'order_ref');
    }
}
