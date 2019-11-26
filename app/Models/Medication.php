<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = ['drug_name','dosage','strength','route','frequency','duration','length','quantity','immunization_llin','immunization_deworming','immunization_vitamin_A','immunization_status','immunization_visits','immunization_vaccines', 'uuid', 'user_uuid'];  

}
