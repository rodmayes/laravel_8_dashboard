@component('mail::message')
# Booking confirmation

Your booking:

@component('mail::table')
|               | Info          |
| ------------- |:-------------:|
| Date          | {{ $booking->started_at->format('d-m-Y') }} |
| Time          | {{ $timetable->name }} |
| Resource      | {{ $resource->name }} |
@endcomponent

@component('mail::button', ['url' => $url])
View booking in Playtomic
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
