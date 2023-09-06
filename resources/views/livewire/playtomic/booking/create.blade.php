<div class="row">
    <div class="card card-primary col-6">
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="card-body">
                @error('error') <small class="text-danger">{{ $message }}</small> @enderror
                <div class="row">
                    <div class="form-group {{ $errors->has('booking.club_id') ? 'invalid' : '' }} col-4">
                        <label class="form-label required" for="club_id">{{ trans('playtomic.bookings.fields.club') }}</label>
                        <x-select-list class="form-control" required id="club_id" name="club_id" :options="$this->listsForFields['club']" wire:model="booking.club_id"/>
                        <small class="text-danger">
                            {{ $errors->first('booking.club_id') }}
                        </small>
                        <div class="help-block">
                            {{ trans('playtomic.bookings.fields.club_helper') }}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('booking.started_at') ? 'is-invalid' : '' }} col-4">
                        <label class="form-label required" for="started_at">{{ trans('playtomic.bookings.fields.started_at') }}</label>
                        <div class="form-group">
                            <div class="input-group date" id="started_at">
                                <input class="form-control flatpickr flatpickr-input" type="text" wire:model="booking.started_at" required>
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
                    <div class="form-group {{ $errors->has('booking.timetable_id') ? 'invalid' : '' }} col-4">
                        <label class="form-label required" for="timetable_id">{{ trans('playtomic.bookings.fields.timetable') }}</label>
                        <x-select-list class="form-control" required id="timetable_id" name="timetable_id" :options="$this->listsForFields['timetable']" wire:model="booking.timetable_id"/>
                        <small class="text-danger">
                            {{ $errors->first('booking.timetable_id') }}
                        </small>
                        <div class="help-block">
                            {{ trans('playtomic.bookings.fields.resource_helper') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group {{ $errors->has('resources') ? 'invalid' : '' }} col-11">
                        <label class="form-label required" for="resources">{{ trans('playtomic.bookings.fields.resource') }}</label>
                        <x-select-list class="form-control" required id="resources" name="resources" :options="$this->listsForFields['resource']" wire:model="resources"  multiple=""/>
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
                    <p class="col-2">
                        <button class="btn btn-xs btn-warning" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Log</button>
                    </p>
                    <div class="collapse col-12" id="collapseExample">
                        <div class="form-group {{ $errors->has('booking.log') ? 'invalid' : '' }} col-12">
                            <label class="form-label" for="log">{{ trans('playtomic.bookings.fields.log') }}</label>
                            <input class="form-control" type="text" name="log" id="log" wire:model.defer="booking.log">
                            <small class="text-danger">
                                {{ $errors->first('booking.log') }}
                            </small>
                            <div class="help-block">
                                {{ trans('playtomic.bookings.fields.log_helper') }}
                            </div>
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

<script type="text/javascript">
    window.onload = (event) => {
        var today = new Date();
        var nextBook = new Date;
        $('.flatpickr-input').datetimepicker({
            locale: 'es',
            format: 'DD-MM-YYYY',
            extraFormats: ['YYYY-MM-DD'],
            'defaultDate' : nextBook.setDate(today.getDate()+3)
        });
    };
</script>
