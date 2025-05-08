<x-mail::message>
    # Magik numbers

    These are the lottery numbers that have been sent to you!

    @foreach($combinations as $combination)
        @php sort($combination) @endphp
        {!! implode(', ', $combination) !!}
    @endforeach

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
