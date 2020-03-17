<!DOCTYPE html>
<head>
    <title>Order Confirmation</title>
</head>
<body>

    <p>Dear {{ $order->first_name }},</p>
    <p>Thank you for your drug order on Nello. This mail is to confirm we have received your order and started putting together your drug(s).</p>
    <p>Order request: {{ $order->order_ref }}</p>
    <p>We will send you a confirmatory email once payment has been verified.</p>
    <hr/>
    <p>From the Nello team</p>
    <p>www.asknello.com</p>
    <p>@asknello</p>

</body>
