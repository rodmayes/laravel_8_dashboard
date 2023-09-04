<div class="row">
    <div class="card card-primary col-6">
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="card-body">
                <div class="form-group {{ $errors->has('club.name') ? 'invalid' : '' }}">
                    <label class="form-label required" for="name">{{ trans('playtomic.clubs.fields.name') }}</label>
                    <input class="form-control" type="text" name="name" id="name" required wire:model.defer="club.name">
                    <div class="validation-message">
                        {{ $errors->first('club.name') }}
                    </div>
                    <div class="help-block">
                        {{ trans('playtomic.clubs.fields.name_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('club.playtomic_id') ? 'invalid' : '' }}">
                    <label class="form-label required" for="playtomic_id">{{ trans('playtomic.clubs.fields.playtomic_id') }}</label>
                    <input class="form-control" type="text" name="playtomic_id" id="playtomic_id" required wire:model.defer="club.playtomic_id">
                    <div class="validation-message">
                        {{ $errors->first('club.playtomic_id') }}
                    </div>
                    <div class="help-block">
                        {{ trans('playtomic.clubs.fields.playtomic_id_helper') }}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('club.days_min_booking') ? 'invalid' : '' }}">
                    <label class="form-label required" for="days_min_booking">{{ trans('playtomic.clubs.fields.days_min_booking') }}</label>
                    <input class="form-control" type="number" name="days_min_booking" id="days_min_booking" required wire:model.defer="club.days_min_booking">
                    <div class="validation-message">
                        {{ $errors->first('club.days_min_booking') }}
                    </div>
                    <div class="help-block">
                        {{ trans('playtomic.clubs.fields.days_min_booking_helper') }}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('playtomic.clubs.index') }}" class="btn btn-secondary">
                    {{ trans('global.cancel') }}
                </a>
                <button class="btn btn-primary mr-2 float-right" type="submit">{{ trans('global.save') }}</button>
            </div>
        </form>
    </div>
</div>
