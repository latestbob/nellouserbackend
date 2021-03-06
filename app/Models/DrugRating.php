<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugRating extends Model
{
    protected $fillable = [
        'drug_id',
        'user_id',
        'rating',
        'comment'
    ];
}
