<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyDrug extends Model
{
    protected $fillable = ['brand', 'name', 'price', 'image', 'category', 'uuid'];
}
