<div class="bg-gray-200 p-3">
    <div class="px-2 py-1 flex w-full">
        <div class="w-4/6 font-bold">
            {{ $date->format('F Y') }}
        </div>

        <div wire:click.prefetch="previousMonth()" class="w-1/6 text-right hover:text-gray-900 cursor-pointer" >
            <i class="fa fa-arrow-left"></i>
        </div>

        <div wire:click.prefetch="nextMonth()" class="w-1/6 text-right hover:text-gray-900 cursor-pointer" >
            <i class="fa fa-arrow-right"></i>
        </div>
    </div>

    <div class="flex flex-wrap text-xs text-center transition" wire:loading.class="opacity-20">
        <div class="flex w-full bg-yellow-50 h6">
            <div class="border" style="width: 14.28%">Mo</div>
            <div class="border" style="width: 14.28%">Tu</div>
            <div class="border" style="width: 14.28%">We</div>
            <div class="border" style="width: 14.28%">Th</div>
            <div class="border" style="width: 14.28%">Fr</div>
            <div class="border" style="width: 14.28%">Sa</div>
            <div class="border" style="width: 14.28%">Su</div>
        </div>
        @php
            $startdate = $date->clone()->startOfMonth()->startOfWeek()->subDay()->addDAy(1);
            $enddate = $date->clone()->endOfMonth()->endOfWeek()->subDay()->addDay(1);
            $loopdate = $startdate->clone();
            $month = $date->clone();
        @endphp

        @while ($loopdate < $enddate)
            <div style="width: 14.28%"
                 class="h8 hover:font-bold border
                     @if ($loopdate < $month->startOfMonth() || $loopdate > $month->endOfMonth())
                         opacity-50
                     @endif
                     @if($loopdate->isCurrentWeek())
                        bg-gray-50
                     @endif
                     @if($loopdate->isToday())
                        bg-yellow-400 text-white
                     @endif
                     ">
                <a class="block px-4 py-1 text-sm hover:bg-gray-600 hover:text-gray" href="{{ route('playtomic.bookings.create', $loopdate->format('Y-m-d')) }}">
                @php
                    $items = array_map(function($item){ return explode(" ",$item)[0];},array_column($bookings,'started_at'));
                @endphp
                @if(($day_key = array_search($loopdate->format('Y-m-d'), $items)) !== FALSE)
                    <span class="text-yellow-600 font-bold" data-popover-target="popover-{{$loopdate}}">
                        {{ $loopdate->format('j') }}
                    </span>
                    <div data-popover id="popover-{{$loopdate}}" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                        <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Booking</h3>
                        </div>
                        <div class="px-3 py-2">
                            <p>Date: {{$loopdate->format('Y-m-d')}}</p>
                            <p>Hour's: @foreach(explode(",",$bookings[$day_key]['timetables']) as $timetable) {{\App\Models\Timetable::find($timetable)->name ?? 'none'}} @endforeach </p>
                        </div>
                        <div data-popper-arrow></div>
                    </div>
                @else
                    {{ $loopdate->format('j') }}
                @endif
                </a>

            </div>
            @php($loopdate->addDay())
        @endwhile
    </div>
</div>
