<div class="row">
    <div class="col-6">
        <div class="card card-success">
            <form wire:submit.prevent="submit" class="pt-3">
                <div class="card-body">
                    @error('errors') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                    <div class="row">
                        <div class="form-group {{ $errors->has('booking.started_at') ? 'is-invalid' : '' }} col-4">
                            <label class="form-label required" for="started_at">{{ trans('playtomic.bookings.fields.started_at') }}</label>
                            <div class="form-group">
                                <div class="input-group date" id="started_at">
                                    <input class="form-control flatpickr flatpickr-input" type="text" wire:model="booking.started_at">
                                    <div class="input-group-append" >
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <small class="text-danger">
                                {{ $errors->first('booking.started_at') }}
                            </small>
                            <div class="help-block">
                                {{ trans('playtomic.bookings.fields.started_at_helper') }}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('booking.timetable_id') ? 'is-invalid' : '' }} col-4">
                            <label class="form-label required" for="timetable_id">{{ trans('playtomic.bookings.fields.timetable') }}</label>
                            <x-select-list class="form-control" required id="timetable_id" name="timetable_id" :options="$this->listsForFields['timetable']" wire:model="booking.timetable_id" />
                            <small class="text-danger">
                                {{ $errors->first('booking.timetable_id') }}
                            </small>
                            <div class="help-block">
                                {{ trans('playtomic.bookings.fields.resource_helper') }}
                            </div>
                        </div>
                        <div class="form-group col-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" value="1" class="custom-control-input" id="ck_public" name="ck_public" wire:model="booking.public" checked>
                                <label class="custom-control-label" for="ck_public">{{ trans('playtomic.bookings.fields.is_public') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group {{ $errors->has('booking.club_id') ? 'is-invalid' : '' }} col-6">
                            <label class="form-label required" for="club_id">{{ trans('playtomic.bookings.fields.club') }}</label>
                            <x-select-list class="form-control" required id="club_id" name="club_id" :options="$this->listsForFields['club']" wire:model="booking.club_id"/>
                            <small class="text-danger">
                                {{ $errors->first('booking.club_id') }}
                            </small>
                            <div class="help-block">
                                {{ trans('playtomic.bookings.fields.club_helper') }}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('resources') ? 'is-invalid' : '' }} col-6">
                            <label class="form-label required" for="resources">{{ trans('playtomic.bookings.fields.resource') }}</label>
                            <x-select-list class="form-control" required id="resources" name="resources" :options="$this->listsForFields['resources']" wire:model="resources" />
                            <small class="text-danger">
                                {{ $errors->first('resources') }}
                            </small>
                            <div class="help-block">
                                {{ trans('playtomic.bookings.fields.resource_helper') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('playtomic.bookings.index') }}" class="btn btn-secondary">{{ trans('global.cancel') }}</a>
                    <button class="btn btn-primary mr-2 float-right" type="submit">{{ trans('global.save') }}</button>
                    <button class="btn btn-success booking-link float-right mr-2" wire:click="generate" type="button">Generate</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-6">
        <div class="card card-success">
            <div class="card-title p-2">
                <h4>
                    {{ trans('playtomic.bookings.prebooking.title_singular') }}
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @if (session()->has('booking-action'))
                        <div class="callout callout-info col-12">
                            <h5>Result</h5>
                            <p>{{ session('booking-action') }}</p>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <p class="mb-2">
                        @if($url_prebooking)
                            <a class="text-blue-500" href="{{$url_prebooking['url']}}" target="_blank">{{ $url_prebooking['name'] }} </a>
                            <button class="btn btn-warning" wire:click="preBooking({{$booking}})" type="button">Generate checkout url</button>
                            <button class="btn btn-success" wire:click="booking({{$booking}})" type="button">Booking</button>
                        @endif
                    </p>
                    <p class="mb-2">
                        @if($url_checkout)
                            <a class="text-blue-500" href="{{$url_checkout['url']}}" target="_blank" id="url-iframe-checkout">Confirmation {{ $url_checkout['name'] }} </a>
                        @endif
                    </p>
                    @if($execution_response)
                        <small class="text-danger">{{$execution_response}}</small>
                    @endif
                    @error('action') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {

            flatpickr('.flatpickr-input', {
                dateFormat: 'd-m-Y',
                altFormat: "F j, Y",
                minTime: "08:00"
            });

        });
    </script>
@endpush
