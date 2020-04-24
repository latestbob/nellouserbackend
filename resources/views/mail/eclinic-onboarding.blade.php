<!DOCTYPE html>
<head>
    <title>Nello On-boarding</title>
</head>
<body>

<p>Dear {{ $user->firstname }},</p>
<p>An account has been created for you on Nello</p>
<p>Nello is your medical Personal Assistant.</p>

<hr/>
<br>

<p>To reset your password use the password reset code below</p>
<p>Code: <b>{{ $code }}</b></p>
<p>Note: The above code will expire in an hour time from when it was generated</p>

<br>

<p><i>Ignore this message if you did not initiate this password reset process.</i></p>

</body>
