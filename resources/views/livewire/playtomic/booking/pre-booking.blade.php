<div class="row">
    <div class="col-6">
        <div class="card card-success">
            <form wire:submit.prevent="submit" class="pt-3">
                <div class="card-body">
                    @error('action') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                    <div class="row">
                        <div class="form-group bg-gray-light {{ $errors->has('booking.started_at') ? 'is-invalid' : '' }} col-6">
                            <label class="form-label required" for="started_at">{{ trans('playtomic.bookings.fields.started_at') }}</label>
                            <x-date-picker class="form-control" id="started_at" name="started_at" wire:model="booking.started_at" inline required/>
                            <small class="text-danger">
                                {{ $errors->first('booking.started_at') }}
                            </small>
                            <div class="help-block">
                                {{ trans('playtomic.bookings.fields.started_at_helper') }}
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group {{ $errors->has('booking.club_id') ? 'invalid' : '' }}">
                                <label class="form-label required" for="club_id">{{ trans('playtomic.bookings.fields.club') }}</label>
                                <x-select-list class="form-control" required id="club_id" name="club_id" :options="$this->listsForFields['club']" wire:model="booking.club_id"/>
                                <small class="text-danger">
                                    {{ $errors->first('booking.club_id') }}
                                </small>
                                <div class="help-block">
                                    {{ trans('playtomic.bookings.fields.club_helper') }}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('timetables') ? 'is-invalid' : '' }}">
                                <label class="form-label required" for="timetables">{{ trans('playtomic.bookings.fields.timetable') }}</label>
                                <x-select-list class="form-control" required id="timetables" name="timetables" :options="$this->listsForFields['timetable']" wire:model="timetables" multiple/>
                                <small class="text-danger">
                                    {{ $errors->first('timetables') }}
                                </small>
                                <div class="help-block">
                                    {{ trans('playtomic.bookings.fields.timetable_helper') }}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('resources') ? 'invalid' : '' }} ">
                                <label class="form-label required" for="resources">{{ trans('playtomic.bookings.fields.resource') }}</label>
                                <x-select-list class="form-control" required id="resources" name="resources" :options="$this->listsForFields['resource']" wire:model="resources"  multiple/>
                                <small class="text-danger">
                                    {{ $errors->first('resources') }}
                                </small>
                                <div class="help-block">
                                    {{ trans('playtomic.bookings.fields.resource_helper') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" value="0" class="custom-control-input" id="ck_public" name="ck_public" wire:model="booking.public" checked>
                                <label class="custom-control-label" for="ck_public">{{ trans('playtomic.bookings.fields.is_public') }}</label>
                            </div>
                        </div>
                        <div class="form-group form-inline {{ $errors->has('booking.booking_preference') ? 'invalid' : '' }} col-6">
                            <label class="form-label required" for="booking_preference">{{ trans('playtomic.bookings.fields.preference') }}</label>
                            <x-select-list class="form-control" required id="booking_preference" name="booking_preference" :options="$this->listsForFields['booking_preference']" wire:model="booking.booking_preference"/>
                            <small class="text-danger">
                                {{ $errors->first('booking.booking_preference') }}
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
                <h4>Booking</h4>
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
                                    <p>{{$l}}</p>
                                @endforeach
                            </div>
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


        });
    </script>
@endpush
