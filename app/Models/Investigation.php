<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investigation extends Model
{
    protected $fillable = ['name','note','result','result_comment','idate', 'uuid', 'user_uuid'];  

}
