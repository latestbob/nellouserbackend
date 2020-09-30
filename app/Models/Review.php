<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['drug_uuid', 'name', 'review', 'rating', 'user_id'];

    public function drug()
    {
        return $this->belongsTo('App\Models\PharmacyDrug', 'drug_uuid', 'uuid');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
