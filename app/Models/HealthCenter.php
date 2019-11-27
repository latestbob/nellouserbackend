<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCenter extends Model
{

    protected $fillable = ['name','address1','center_type','phone','email','address2','state','city','is_active', 'uuid', 'vendor_id'];  

}
