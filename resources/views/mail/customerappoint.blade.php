@component('mail::message')
# Appointment Booked Successfully

Below are details of the appointment

Date :    

<br>

Time :     Time
<br>

Doctor:     Doctors Name

<br>

Doctor specializations: Doctor


<br>
Kindly  Click to Join appointment at the set time.
@component('mail::button', ['url' => '')
Appointment Link
@endcomponent

Thanks,<br>
nello
@endcomponent
