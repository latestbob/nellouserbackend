<!DOCTYPE html>
<head>
    <title>Order Payment Notification</title>
</head>
<body>

<p>Dear Admin,</p>
<p>This mail is meant to notify you that {{ $order->firstname }} {{ $order->lastname }} has paid the sum of <b>N{{ $order->amount }}</b> for the item(s) they ordered.</p>

@if($order->delivery_method == 'pickup')

<p> You may now begin processing order items, customer had to notify to pickup order at {{$order->pickup_location->address}}</p>


@else
<p>You may now begin processing order items to be delivered to  {{$order->location->address}} </p>


@endif

<p>Order reference: {{ $order->order_ref }}</p>
<hr/>
<p>From the Nello team on {{ \Carbon\Carbon::parse($order->created_at)->format('h:ia F dS, Y') }}</p>
<p>www.asknello.com</p>

</body>
