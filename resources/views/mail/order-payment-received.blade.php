<!DOCTYPE html>
<head>
    <title>Order Payment Received</title>
</head>
<body>

<p>Dear {{ $order->firstname }},</p>
<p>Thank you for your drug order on Nello. This mail is to confirm that we have received your payment of <b>N{{ $order->amount }}</b>.</p>
<p>{{ $order->delivery_method == 'shipping' ? 'Your drug(s) will now be shipped to you.' : "You maybe now come to pickup your drug(s) at {$order->location->name} - {$order->location->address}" }}</p>
<p>Order reference: {{ $order->order_ref }}</p>
<hr/>
<p>From the Nello team</p>
<p>www.asknello.com</p>

</body>
