<!DOCTYPE html>
<head>
    <title>Nello Point</title>
</head>
<body>

<p>Hello {{ $point->user->firstname }},</p>
<p>You have just earned a point on the Nello Platform.</p>
<p><b>Points Earned Today:</b> {{ $point->total_points_earned_today }}</p>
<p><b>Total Point:</b> {{ $point->point }}</p>
<p><b>Total Point Value:</b> N{{ ($rules->point_value * $point->point) }}</p>
<hr/>
<p>From the Nello team</p>
<p>www.asknello.com</p>

</body>
