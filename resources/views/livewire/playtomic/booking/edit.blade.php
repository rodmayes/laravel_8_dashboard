<div>
    <h1 class="h3">Edit Booking</h1>
    <div class="w-full p-4 border border-gray-200 bg-white rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="grid lg:grid-cols-2 md:grid-cols-2 xs:grid-cols-1 xl:grid-cols-1">
                @error('error') <small class="text-danger">{{ $message }}</small> @enderror
                <div class="mr-5 {{ $errors->has('booking.started_at') ? 'is-invalid' : '' }}">
                    <label class="form-label required" for="started_at">{{ trans('playtomic.bookings.fields.started_at') }}</label>
                    <div inline-datepicker datepicker-buttons data-date="{{ isset($booking->started_at) ? $booking->started_at->format('d-m-Y') : null }}"
                         datepicker-format="dd-mm-yyyy" wire:model="booking.started_at" required wire:ignore id="started_at" week-start="1"></div>
                    <small class="text-danger">
                        {{ $errors->first('booking.started_at') }}
                    </small>
                    <div class="help-block">
                        {{ trans('playtomic.bookings.fields.started_at_helper') }}
                    </div>
                </div>

                <div class="w-full">
                    <div class="{{ $errors->has('booking.club_id') ? 'invalid' : '' }}">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="club_id">{{ trans('playtomic.bookings.fields.club') }}</label>
                        <x-select
                            placeholder="Select one club"
                            :options="$this->listsForFields['club']"
                            wire:model="booking.club_id"
                            option-value="id"
                            option-label="name"
                        />
                        <small class="text-danger">
                            {{ $errors->first('booking.club_id') }}
                        </small>
                        <div class="help-block">
                            {{ trans('playtomic.bookings.fields.club_helper') }}
                        </div>
                    </div>
                    <div class="mb-2 {{ $errors->has('resources') ? 'invalid' : '' }} ">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="resources">{{ trans('playtomic.bookings.fields.resource') }}</label>
                        <x-select
                            placeholder="Select one or many resources"
                            multiselect
                            :options="$this->listsForFields['resource']"
                            wire:model.defer="resources"
                            option-value="id"
                            option-label="name"

                        />
                        <small class="text-danger">
                            {{ $errors->first('resources') }}
                        </small>
                        <div class="help-block">
                            {{ trans('playtomic.bookings.fields.resource_helper') }}
                        </div>
                    </div>
                    <div class="mb-2 {{ $errors->has('timetables') ? 'is-invalid' : '' }}">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="timetables">{{ trans('playtomic.bookings.fields.timetable') }}</label>
                        <x-select
                            placeholder="Select one or many timetables"
                            multiselect
                            :options="$this->listsForFields['timetable']"
                            wire:model.defer="timetables"
                            option-value="id"
                            option-label="name"
                        />
                        <small class="text-danger">
                            {{ $errors->first('timetables') }}
                        </small>
                        <div class="help-block">
                            {{ trans('playtomic.bookings.fields.timetable_helper') }}
                        </div>
                    </div>

                    <div class="mb-2 {{ $errors->has('booking.booking_preference') ? 'invalid' : '' }} 4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="booking_preference">Preference: </label>
                        <x-select
                            placeholder="Select a preference"
                            :options="$this->listsForFields['booking_preference']"
                            wire:model.defer="booking.booking_preference"
                            option-value="id"
                            option-label="name"
                        />
                        <small class="text-danger">
                            {{ $errors->first('booking.booking_preference') }}
                        </small>
                        <div class="help-block">
                            {{ trans('playtomic.bookings.fields.resource_helper') }}
                        </div>
                    </div>
                    <div class="mb-2 {{ $errors->has('booking.status') ? 'invalid' : '' }}">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="status">{{ trans('playtomic.bookings.fields.status') }}: </label>
                        <x-select
                            placeholder="Select a status"
                            :options="$this->listsForFields['status']"
                            wire:model.defer="booking.status"
                            option-value="id"
                            option-label="name"
                        />
                        <small class="text-danger">
                            {{ $errors->first('booking.status') }}
                        </small>
                        <div class="help-block">
                            {{ trans('playtomic.bookings.fields.status_helper') }}
                        </div>
                    </div>
                    <div class="mb-2 {{ $errors->has('booking.player_email') ? 'invalid' : '' }} 4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="booking_player">Player: </label>
                        <x-select
                            placeholder="Select a player"
                            :options="$this->listsForFields['players']"
                            wire:model="booking.player_email"
                            option-value="email"
                            option-label="name"
                        />
                        <small class="text-danger">
                            {{ $errors->first('booking.player_email') }}
                        </small>
                        <div class="help-block">
                            {{ trans('playtomic.bookings.fields.resource_helper') }}
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <div class="mb-2 {{ $errors->has('booking.duration') ? 'invalid' : '' }} ">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="booking_player">Duration: </label>
                            <x-select
                                placeholder="Select a duration"
                                :options="$this->listsForFields['duration']"
                                wire:model="booking.duration"
                                option-value="id"
                                option-label="name"
                            />
                            <small class="text-danger">
                                {{ $errors->first('booking.duration') }}
                            </small>
                            <div class="help-block">

                            </div>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" id="ck_public" name="ck_public" wire:model="booking.public" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full
                            peer-checked:after:border-white after:content-[''] after:absolute after:top-[16px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5
                            after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                            </div>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ trans('playtomic.bookings.fields.is_public') }}</span>
                        </label>
                        <div class="grid">
                            <p>Is booked {{ $booking->booked_at ? $booking->booked_at->format('d-m-Y H:i:s') : null }}</p>
                            <button type="button"
                                    class="float-right px-3 py-1 text-xs text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300
                                    font-medium rounded-lg text-center mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900"
                                    wire:click="toggleBooked">
                                @if($booking->isBooked) Set no booked @else Set Booked @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full items-center p-6 space-x-3 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="submit" class="float-right text-white bg-teal-400 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5
                text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    {{ trans('global.save') }}
                </button>
                <a href="{{ route('playtomic.bookings.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border
                border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    {{ trans('global.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        const datepickerEl = document.getElementById('started_at');

        datepickerEl.addEventListener('changeDate', (event) => {
            window.livewire.emit('dateSelected', event.detail.date);
        });
    </script>
@endpush
