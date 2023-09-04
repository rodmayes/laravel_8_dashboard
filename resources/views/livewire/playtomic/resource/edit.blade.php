<div class="row">
    <div class="card card-primary col-6">
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="card-body">
                <div class="form-group {{ $errors->has('resource.club_id') ? 'invalid' : '' }}">
                    <label class="form-label required" for="club_id">{{ trans('playtomic.resources.fields.club') }}</label>
                    <x-select-list class="form-control" required id="club_id" name="club_id" :options="$this->listsForFields['club']" wire:model="resource.club_id" />
                    <div class="validation-message">
                        {{ $errors->first('resource.club_id') }}
                    </div>
                    <div class="help-block">
                        {{ trans('playtomic.resources.fields.club_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('resource.name') ? 'invalid' : '' }}">
                    <label class="form-label required" for="name">{{ trans('playtomic.resources.fields.name') }}</label>
                    <input class="form-control" type="text" name="name" id="name" required wire:model.defer="resource.name">
                    <div class="validation-message">
                        {{ $errors->first('resource.name') }}
                    </div>
                    <div class="help-block">
                        {{ trans('playtomic.resources.fields.name_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('resource.playtomic_id') ? 'invalid' : '' }}">
                    <label class="form-label required" for="playtomic_id">{{ trans('playtomic.resources.fields.playtomic_id') }}</label>
                    <input class="form-control" type="text" name="playtomic_id" id="playtomic_id" required wire:model.defer="resource.playtomic_id">
                    <div class="validation-message">
                        {{ $errors->first('resource.playtomic_id') }}
                    </div>
                    <div class="help-block">
                        {{ trans('playtomic.resources.fields.playtomic_id_helper') }}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('resource.priority') ? 'invalid' : '' }}">
                    <label class="form-label required" for="priority">{{ trans('playtomic.resources.fields.priority') }}</label>
                    <input class="form-control" type="number" name="priority" id="priority" required wire:model.defer="resource.priority">
                    <div class="validation-message">
                        {{ $errors->first('resource.priority') }}
                    </div>
                    <div class="help-block">
                        {{ trans('playtomic.resources.fields.priority_helper') }}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('playtomic.resources.index') }}" class="btn btn-secondary">{{ trans('global.cancel') }}</a>
                <button class="btn btn-primary mr-2 float-right" type="submit">{{ trans('global.save') }}</button>
            </div>
        </form>
    </div>
</div>
