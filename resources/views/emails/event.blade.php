@component('mail::message')
Hello! 👋

A new Event: {{ $emailData->name }} was created.
Please click on the below button to check it out!

@component('mail::button', ['url' => config('app.url')])
Vist our Website
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
