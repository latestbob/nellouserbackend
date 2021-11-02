<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnersController extends Controller
{
    public function index(Request $request)
    {
        return Partner::paginate($request->size ?? 15);
    }


    public function create(PartnerRequest $request)
    {
        $data = $request->validated();
        $data['api_key'] = '';
        Partner::create($data);
    }


}
