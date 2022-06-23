@component('mail::message')
# New Nello Appointment Scheduled

Hi {{ $customerdetails['doctor'] }} , 
<br>
A new Nello Online Appointment has been scheduled with you.
<br>

See details of the schedule appointment below


<br>

Date :  {{ $customerdetails['date'] }} <br>

Time : {{ $customerdetails['time'] }}<br>

Patient Name : {{ $customerdetails['username'] }}<br>




Appointment Link : <a href="{{ $customerdetails['link'] }}">{{ $customerdetails['link'] }}</a>


@component('mail::button', ['url' =>  $customerdetails['link']])
Appointment Link
@endcomponent

Thanks for choosing Nello,<br>
<a href="https://asknello.com">Asknello</a>
@endcomponent
