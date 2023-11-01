@component('mail::message')
# Email test

@component('mail::table')
|               | Info          |
| ------------- |:-------------:|
| Title         | {{ $details['title'] }} |
| Body          | {{ $details['body'] }} |
| Sended        | {{ now() }} |
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
