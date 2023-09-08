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

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <input type="hidden" name="event_id" id="event_id" value="" />
                <input type="hidden" name="appointment_id" id="appointment_id" value="" />
                <div class="modal-body">
                    <h4>Edit Booking</h4>
                    Start time:
                    <br />
                    <input type="text" class="form-control" name="start_time" id="start_time">

                    End time:
                    <br />
                    <input type="text" class="form-control" name="finish_time" id="finish_time">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" class="btn btn-primary" id="appointment_update" value="Save">
                </div>
            </div>
        </div>
    </div>
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
                eventClick: function(calEvent, jsEvent, view) {
                    $('#appointment_id').val(calEvent.id);
                    $('#start_time').val(moment(calEvent.start).format('YYYY-MM-DD HH:mm'));
                    $('#finish_time').val(moment(calEvent.end).format('YYYY-MM-DD HH:mm'));
                    $('#editModal').modal();
                },
                events : [
                    @foreach($bookings as $appointment)
                    {
                        id: {{ $appointment['id'] }},
                        title: '{{ $appointment['name'] }}',
                        start: '{{ $appointment['start'] }}',
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

        $(document).ready(function() {
            // ... All the calendar functionality

            $('#appointment_update').click(function(e) {
                e.preventDefault();
                var data = {
                    _token: '{{ csrf_token() }}',
                    appointment_id: $('#appointment_id').val(),
                    start_time: $('#start_time').val(),
                    finish_time: $('#finish_time').val(),
                };

                $.post('{{ route('playtomic.bookings.create') }}', data, function( result ) {
                    $('#calendar').fullCalendar('removeEvents', $('#event_id').val());

                    $('#calendar').fullCalendar('renderEvent', {
                        title: result.appointment.client.first_name + ' ' + result.appointment.client.last_name,
                        start: result.appointment.start_time,
                        end: result.appointment.finish_time
                    }, true);

                    $('#editModal').modal('hide');
                });
            });
        });
    </script>
@endpush
