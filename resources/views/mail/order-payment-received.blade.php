<!DOCTYPE html>
<head>
    <title>Order Payment Received</title>
</head>
<body>

<p>Dear {{ $order->firstname }},</p>
<p>Thank you for your drug order on Nello. This mail is to confirm that we have received your payment of <b>N{{ $order->amount }}</b>.</p>

@if($order->delivery_method == 'pickup')

<p> You can pickup your order from our pickup store at {{$order->pickup_location->address}}</p>


@else
<p>Your order will be shipped to you at {{$order->location->address}} </p>


@endif

<p>Order reference: {{ $order->order_ref }}</p>
<hr/>
<p>From the Nello team</p>
<p>www.asknello.com</p>

</body>
