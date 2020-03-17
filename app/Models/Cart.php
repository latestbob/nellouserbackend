<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    const TAX_FEE = 0.05; //percentage of total price
    protected $fillable = ['cart_uuid', 'drug_id', 'quantity', 'price', 'user_id'];

    public function drug()
    {
        return $this->belongsTo('App\Models\PharmacyDrug', 'drug_id', 'id');
    }
}
