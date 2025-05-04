<div id="booking-show-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full" wire:ignore.self>
    <div class="relative w-full max-w-4xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $selected_booking ? $selected_booking->name : null }} #{{ $selected_booking->id ?: null }}
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="booking-show-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('playtomic.bookings.fields.name') }}: </label>
                        {{ $selected_booking ? $selected_booking->name : null }}
                    </div>
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('playtomic.bookings.fields.club') }}: </label>
                        {{ $selected_booking->club ? $selected_booking->club->name : null }}
                    </div>
                </div>
                <div class="grid grid-cols-2">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('playtomic.bookings.fields.started_at') }}: </label>
                        {{ $selected_booking->started_at ? $selected_booking->started_at->format('d-m-Y') : null }}
                    </div>
                    <div>
                        @if($selected_booking->id != 0)
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('playtomic.bookings.fields.status') }}: </label>
                            @if($selected_booking->status === 'on-time')
                                <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ trans('playtomic.bookings.on-time') }}</span>
                            @elseif($selected_booking->status === 'time-out')
                                <span class="bg-indigo-100 text-indigo-900 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">{{ trans('playtomic.bookings.time-out') }}</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ trans('playtomic.bookings.closed') }}</span>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-2">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('playtomic.bookings.fields.timetable') }}: </label>
                        @if($selected_booking->id != 0)
                            @foreach(explode(",",$selected_booking->timetables) as $id)
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">{{ \App\Models\Timetable::find($id)->name }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div>
                        @if($selected_booking->id != 0)
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('playtomic.bookings.fields.preference') }}: </label>
                            {{ $selected_booking ? $selected_booking->booking_preference : null }}
                        @endif
                    </div>
                </div>
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('playtomic.bookings.fields.resource') }}: </label>
                    @if($selected_booking->id != 0)
                        @foreach(explode(",",$selected_booking->resources) as $id)
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ \App\Models\Resource::find($id)->name }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <a href="{{ route('playtomic.bookings.edit', $selected_booking) }}"
                   type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    {{ trans('global.edit') }}
                </a>
                <button data-modal-hide="booking-show-modal" type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    {{ trans('global.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>
