<?php

namespace App\Traits;

use App\Models\Coupon;

trait CouponCode 
{
    public function computeValue(string $code, float $amount): float
    {
        $coupon = Coupon::where('code', $code)->first();
        if ($coupon) {
            if ($coupon->type === 'amount') {
                return $coupon->value;
            } else if ($coupon->type === 'percentage') {
                return $amount * ($coupon->value / 100.0); 
            }
        }

        return 0;
    }
}