<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPoints extends Model
{
    protected $fillable = ['point', 'customer_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'customer_id', 'id');
    }
}
