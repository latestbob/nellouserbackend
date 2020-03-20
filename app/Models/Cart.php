<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    const TAX_FEE = 0.05; //percentage of total price
    protected $fillable = ['cart_uuid', 'drug_id', 'quantity', 'price', 'prescription', 'user_id', 'vendor_id'];

    public function order()
    {
        $item = $this->belongsTo('App\Models\Order', 'cart_uuid', 'cart_uuid');
        return $item;
    }

    public function drug()
    {
        $item = $this->belongsTo('App\Models\PharmacyDrug', 'drug_id', 'id');
        return $item;
    }
}
