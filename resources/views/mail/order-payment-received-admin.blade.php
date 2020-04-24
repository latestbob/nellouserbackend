<!DOCTYPE html>
<head>
    <title>Order Payment Notification</title>
</head>
<body>

<p>Dear Admin,</p>
<p>This mail is meant to notify you that {{ $order->firstname }} {{ $order->lastname }} has paid the sum of <b>N{{ $order->amount }}</b> for the item(s) they ordered. You may now begin processing delivery of the ordered item(s).</p>
<p>Order reference: {{ $order->order_ref }}</p>
<hr/>
<p>From the Nello team</p>
<p>www.asknello.com</p>

</body>
