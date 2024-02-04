<div class="grid grid-cols-2">
    <div class="w-full p-4 border border-gray-200 bg-gray-50 rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
        <h1 class="h3" >Create Booking</h1>
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="grid grid-cols-2">
                @error('error') <small class="text-danger">{{ $message }}</small> @enderror
                <div class="mb-2 {{ $errors->has('booking.started_at') ? 'is-invalid' : '' }}">
                    <label class="form-label required" for="started_at">{{ trans('playtomic.bookings.fields.started_at') }}</label>
                    <div inline-datepicker datepicker-buttons data-date="{{ isset($booking->started_at) ? $booking->started_at->format('d-m-Y') : now()->format('d-m-Y') }}"
                         datepicker-format="dd-mm-yyyy" wire:model="booking.started_at" required wire:ignore id="started_at"></div>
                    <small class="text-danger">
                        {{ $errors->first('booking.started_at') }}
                    </small>
                    <div class="help-block">
                        {{ trans('playtomic.bookings.fields.started_at_helper') }}
                    </div>
                </div>

                <div class="col-6 pr-0">
                    <div class="form-group {{ $errors->has('booking.club_id') ? 'invalid' : '' }}">
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
                            placeholder="Select many resources"
                            multiselect
                            :options="$this->listsForFields['resource']"
                            wire:model="resources"
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
                            placeholder="Select many timetables"
                            multiselect
                            :options="$this->listsForFields['timetable']"
                            wire:model="timetables"
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
                            wire:model="booking.booking_preference"
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

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="0" class="sr-only peer" id="ck_public" name="ck_public" wire:model="booking.public" checked>
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[0px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ trans('playtomic.bookings.fields.is_public') }}</span>
                    </label>
                </div>

            </div>
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <a href="{{ route('playtomic.bookings.index') }}" class="btn btn-secondary">{{ trans('global.cancel') }}</a>
                <button class="btn btn-primary mr-2 float-right" type="submit">{{ trans('global.save') }}</button>
                <button class="btn btn-success booking-link float-right mr-2" wire:click="generate" type="button">Generate</button>
            </div>

        </form>
    </div>

    <div class="w-full p-4 border border-gray-200 bg-gray-50 rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
        <h1 class="h3">Booking</h1>
        <div class="row">
            @if (session()->has('booking-action'))
                <div class="callout callout-info col-12">
                    <h5>Result</h5>
                    <p>{{ session('booking-action') }}</p>
                </div>
            @endif
        </div>
        <div class="form-group">
            @if($url_prebooking)
                @foreach($url_prebooking as $url)
                <p class="mb-2">
                    <a class="text-blue-500" href="{{$url['url']}}" target="_blank">{{ $url['name'] }} </a>
                    <button class="btn btn-success" wire:click="booking({{$url['resource']}}, {{$url['timetable']}})" type="button">Booking</button>
                @endforeach
                </p>
            @endif
            <p class="mb-2">
                @if($url_checkout)
                    <a class="text-blue-500" href="{{$url_checkout['url']}}" target="_blank" id="url-iframe-checkout">Confirmation {{ $url_checkout['name'] }} </a>
                @endif
            </p>
                @if(count($log) > 0)
                    <div class="callout callout-info">
                        <h5>Process info</h5>
                        @foreach($log as $l)
                                <p>{{ $l }}</p>
                        @endforeach
                    </div>
                @endif
            @error('action') <span class="error invalid-feedback">{{ $message }}</span> @enderror
        </div>
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
