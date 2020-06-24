<!DOCTYPE html>
<head>
    <title>Nello Contact</title>
</head>
<body>

<p>Hello Nello,</p>
<p>{{ $contact->message }}</p><hr/>
<h3>Sender Details:</h3>
<p><b>Name:</b> {{ $contact->name }}</p>
<p><b>Email:</b> {{ $contact->email }}</p>
<p><b>Phone:</b> {{ $contact->phone }}</p>
<hr/>
<p>From the Nello platform</p>
<p>www.asknello.com</p>

</body>
