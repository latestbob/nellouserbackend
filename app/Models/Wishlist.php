<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['id', 'drug_id', 'user_id'];

    public function drug()
    {
        return $this->belongsTo('App\Models\PharmacyDrug', 'drug_id', 'id');
    }
}
