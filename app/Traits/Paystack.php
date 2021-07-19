<?php

namespace App\Traits;

use App\Models\TransactionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait Paystack {

    
    /**
     * @param string $reference
     * @return array
     */
    public function verify(string $reference, float $amount)
    {
        //$user = Auth::user();
        // initiate the Library's Paystack Object
        $paystack = new \Yabacon\Paystack(config('paystack.secret'));
        try {
            // verify using the library
            $tranx = $paystack->transaction->verify([
                'reference' => $reference, // unique to transactions
            ]);
            //return json_encode($tranx);
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }

        if ('success' === $tranx->data->status) {
            //return ['msg' => 'Atta girl', 'data' => json_encode($tranx)];
            // transaction was successful...
            // please check other things like whether you already gave value for this ref
            // if the email matches the customer who owns the product etc
            // Give value
            $trans = TransactionLog::where('gateway_reference', $reference)->first();

            if ($trans) return [
                'status' => false,
                'message' => 'This reference has been used.'
            ];

            if ($amount > $tranx->data->amount) return [
                'status' => false,
                'message' => 'Incomplete payment.'
            ];

            /*if ($trans->email != ($trans->order->email ?: $trans->order->customer->email)) return [
                'status' => false,
                'message' => 'You did not initiate that transaction'
            ];

            if ($trans->order->is_paid == true) return [
                'status' => true,
                'message' => 'That transaction has already been paid'
            ]; */

            /*if (!CardAuthorization::where('email', $user->email)->exists()) {

                CardAuthorization::create([
                    'uuid' => Str::uuid()->toString(),
                    'email' => $user->email,
                    'authorization' => $tranx->data->authorization
                ]);
            }*/

            //SendOrderMail::dispatch($trans->order, SendOrderMail::ORDER_PAYMENT_RECEIVED);

            //$trans->order->update(['is_paid' => true]);

            return [
                'status' => true,
                'message' => 'Paid successfully'
            ];
        }

        return [
            'status' => false,
            'message' => 'Payment verification failed'
        ];
    }



}