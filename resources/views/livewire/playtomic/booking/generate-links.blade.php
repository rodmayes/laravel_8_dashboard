<div class="col-12">
    <div class="callout callout-warning">
        <h4>
            <i class="fas fa-globe"></i> Booking for: {{$booking->club->name}} on {{ $this->booking->started_at->format('l,  d-m-Y') }}
            <a href="{{ route('playtomic.bookings.edit', $booking) }}" class="btn btn-success float-right">
                {{ trans('playtomic.generate-links.edit') }}
            </a>
        </h4>
    </div>

    <div class="callout callout-info">
        <h5>
            <i class="fas fa-clock"></i> Time
            <button class="btn btn-info btn-sm mr-2 float-right" type="button" id="btn-open-all" >
                {{ trans('playtomic.generate-links.open-all') }}
            </button>
        </h5>
        <div class="p-3 mb-3">
            <div >
                <div class="date text-center">
                    <span id="weekDay" class="weekDay"></span>,
                    <span id="day" class="day"></span> de
                    <span id="month" class="month"></span> del
                    <span id="year" class="year"></span>
                </div>
                <div class="clock text-center">
                    <span id="hours" class="hours"></span> :
                    <span id="minutes" class="minutes"></span> :
                    <span id="seconds" class="seconds"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4 bg-white  table-responsive">
        <div class="pt-3">
            <div class="custom-control custom-switch ml-2">
                <input type="checkbox" class="custom-control-input" id="check_all" name="check_all" onclick="toggle(this);">
                <label class="custom-control-label" for="check_all">Check All</label>
            </div>
        </div>
        <!-- LINKS -->
        <table class="table table-responsive table-sm">
            <thead>
            <tr>
                <th>Name</th>
                <th>Link</th>
                <th>Check</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                @foreach($links as $key => $link)
                <tr>
                    <td>{{ $link['name'] }}</td>
                    <td><a class="btn btn-sm btn-link booking-link" href="{{ $link['url'] }}" target="_blank">Open link</a></td>
                    <td>
                        <div class="custom-control custom-switch ml-2">
                            <input type="checkbox" class="custom-control-input" id="ck_link_{{$key}}" name="ck_link">
                            <label class="custom-control-label" for="ck_link_{{$key}}"></label>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm booking-link float-right" wire:click="booking({{$booking}}, {{ $link['resource'] }}, {{ $link['timetable'] }})">Booking</button>
                        <button data-toggle="modal" data-target="#prebooking-modal" wire:click="preBooking({{$booking}}, {{ $link['resource'] }}, {{ $link['timetable'] }})" class="btn btn-primary btn-sm mr-2 float-right">Prebooking</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- END LINKS -->
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="prebooking-modal" tabindex="-1" role="dialog" aria-labelledby="prebooking-modal-Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="prebooking-modal-Label">Log execution</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>{{$prebooking['message']}}</h5>
                    @foreach($log as $l)
                        <p class="text-sm">{{$l}}</p>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{asset('js/rellotge.js')}}"></script>
<script>
        function openSelecteLinks() {
            var links = []
            var matches = document.querySelectorAll("a.booking-link");
            for(var i = 0; i < matches.length; i++) {
                links.push(matches[i].href);
            }

            links.forEach((item, i) => {
                var el = document.getElementById("ck_link_" + i);
                if (el.checked) {
                    w = window.open(item);
                }
            });
        }

        let element = document.getElementById("btn-open-all");
        element.onclick = function () {
            openSelecteLinks();
        }

        // Open links automatically
        function comprobar() {
            today = new Date();
            hour = today.getHours();
            minutes = today.getMinutes();
            seconds = today.getSeconds();

            if (hour === 8 && minutes === 0 && seconds >= 0 && seconds <= 1) openSelecteLinks();
        }
        setInterval(comprobar, 1000);

        function toggle(source) {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] !== source)
                    checkboxes[i].checked = source.checked;
            }
        }
</script>
 @endpush
