<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaystackPayment extends Model
{
    protected $fillable = ['status','email','amount','reference','payment_type', 'uuid', 'user_uuid'];  

}
