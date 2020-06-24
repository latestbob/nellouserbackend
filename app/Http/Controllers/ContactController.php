<?php

namespace App\Http\Controllers;

use App\ContactMessage;
use App\Jobs\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    /**
     * Send customer message
     *
     * @bodyParam message string required
     */
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|digits_between:11,16',
            'subject' => 'required|string|max:150',
            'message' => 'required|string',
            'user_id' => 'nullable|numeric|exists:users,id'
        ]);

        if ($validator->fails()) return [
            'status' => false,
            'message' => $validator->errors()
        ];

        $data = $validator->validated();
        $data['uuid'] = Str::uuid()->toString();
        ContactMail::dispatch(ContactMessage::create($data));

        return [
            'status' => true,
            'message' => "Message sent successfully"
        ];
    }
}
