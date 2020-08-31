<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['uuid', 'name', 'email', 'phone', 'subject', 'message', 'user_id', 'is_read'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
