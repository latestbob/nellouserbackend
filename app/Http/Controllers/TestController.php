<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    
    public function customers() 
    {
        $customers = User::where('user_type', 'customer')->orderBy('firstname')->get();
        return $customers;
    }
}
