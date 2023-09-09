@extends('layouts.admin')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        {{ trans('playtomic.bookings.title_singular') }}
                        {{ trans('global.list') }}
                        Calendar
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('playtomic.bookings.index') }}">{{ trans('playtomic.bookings.title') }}</a></li>
                        <li class="breadcrumb-item active"> Calendar </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                editable:true,
                headerToolbar: { center: 'dayGridMonth,timeGridWeek' }, // buttons for switching between views
                themeSystem: 'bootstrap',
                initialView: 'dayGridMonth',
                selectable:true,
                selectHelper: true,
                firstDay: 1,
                eventClick: function(event) {
                    console.log(event.event._def.url);
                    if (event.event._def.url) {
                        window.open(event.li.event._def.url);
                        return false;
                    }
                },
                events : [
                    @foreach($bookings as $appointment)
                    {
                        id: {{ $appointment['id'] }},
                        title: '{{ $appointment['name'] }}',
                        start: '{{ $appointment['start'] }}',
                        url: '{{route('playtomic.bookings.edit', $appointment['id'])}}',
                        @if (isset($appointment['end']))
                        end: '{{ $appointment['end'] }}',
                        @endif
                    },
                    @endforeach
                ],
                views: {
                    dayGridMonth: { // name of view
                        titleFormat: { year: 'numeric', month: '2-digit', day: '2-digit' },
                        eventTimeFormat: { // like '14:30:00'
                            hour: '2-digit',
                            minute: '2-digit',
                            meridiem: false,
                            hour12: false
                        }
                    }
                }
            });
            calendar.render();
        });
    </script>
@endpush
