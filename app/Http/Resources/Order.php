<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $items = $this->items();
        return [
            'id' => $this->id,
            'cart_uuid' => $this->cart_uuid,
            'order_ref' => $this->order_ref,
            'customer_id' => $this->customer_id,
            'firstname'  => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address1,
            'amount' => $this->amount,
            //'items' => $items->get(),
            'items_count' => $items->count(),
            'pending' => $items->where('is_ready', 0)->count() > 0 ? 1 : 0,
            'created_at' => $this->created_at
        ];
    }
}
