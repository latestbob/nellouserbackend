@component('mail::message')
# Appointment Booked Successfully

Hi {{ $customerdetails['username'] }} , 
<br>
You have successfully scheduled an Online appointment.
<br>

See details of the schedule appointment below


<br>

Date :  {{ $customerdetails['date'] }} <br>

Time : {{ $customerdetails['time'] }} <br>

Doctor : {{ $customerdetails['doctor'] }} <br>

Doctor AOS : {{ $customerdetails['doctoraos'] }} <br>


Appointment Link : <a href="{{ $customerdetails['link'] }}">{{ $customerdetails['link'] }}</a>


@component('mail::button', ['url' => $customerdetails['link'] ])
Appointment Link
@endcomponent

Thanks for choosing Nello,<br>
<a href="https://asknello.com">Asknello</a>
@endcomponent
