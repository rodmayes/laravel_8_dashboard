<div class="row">
    <div class="card card-primary col-6">
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="card-body">
                @error('error') <small class="text-danger">{{ $message }}</small> @enderror
                <div class="row">
                    <div class="form-group border bg-gray-light {{ $errors->has('booking.started_at') ? 'is-invalid' : '' }} col-6">
                        <label class="form-label required" for="started_at">{{ trans('playtomic.bookings.fields.started_at') }}</label>
                        <x-date-picker class="form-control" id="started_at" name="started_at" wire:model="booking.started_at" inline required value="{{$booking->started_at}}"/>
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
                    </div>
                </div>
                <div class="row">
                    <div class="form-group {{ $errors->has('resources') ? 'invalid' : '' }} col-12">
                        <label class="form-label required" for="resources">{{ trans('playtomic.bookings.fields.resource') }}</label>
                        <x-select-list class="form-control" required id="resources" name="resources" :options="$this->listsForFields['resource']" wire:model="resources" multiple/>
                        <small class="text-danger">
                            {{ $errors->first('resources') }}
                        </small>
                        <div class="help-block">
                            {{ trans('playtomic.bookings.fields.resource_helper') }}
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
                    <div class="form-group form-inline {{ $errors->has('booking.status') ? 'invalid' : '' }} col-4">
                        <label class="form-label required" for="status">{{ trans('playtomic.bookings.fields.status') }}</label>
                        <x-select-list class="form-control" required id="status" name="booking_preference" :options="$this->listsForFields['status']" wire:model="booking.status"/>
                        <small class="text-danger">
                            {{ $errors->first('booking.status') }}
                        </small>
                        <div class="help-block">
                            {{ trans('playtomic.bookings.fields.status_helper') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('playtomic.bookings.index') }}" class="btn btn-secondary">{{ trans('global.cancel') }}</a>
                <button class="btn btn-primary mr-2 float-right" type="submit">{{ trans('global.save') }}</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $( document ).ready(function() {

        });
    </script>
@endpush
