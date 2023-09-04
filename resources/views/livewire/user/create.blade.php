<div class="row">
    <div class="card card-success col-8">
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="card-body">
                <div class="form-group {{ $errors->has('user.name') ? 'invalid' : '' }}">
                    <label class="form-label required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                    <input class="form-control" type="text" name="name" id="name" required wire:model.defer="user.name">
                    <div class="validation-message">
                        {{ $errors->first('user.name') }}
                    </div>
                    <div class="help-block">
                        {{ trans('cruds.user.fields.name_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('user.email') ? 'invalid' : '' }}">
                    <label class="form-label required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                    <input class="form-control" type="email" name="email" id="email" required wire:model.defer="user.email">
                    <div class="validation-message">
                        {{ $errors->first('user.email') }}
                    </div>
                    <div class="help-block">
                        {{ trans('cruds.user.fields.email_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('user.password') ? 'invalid' : '' }}">
                    <label class="form-label required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                    <input class="form-control" type="password" name="password" id="password" required wire:model.defer="password">
                    <div class="validation-message">
                        {{ $errors->first('user.password') }}
                    </div>
                    <div class="help-block">
                        {{ trans('cruds.user.fields.password_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('user.playtomic_id') ? 'invalid' : '' }}">
                    <label class="form-label" for="playtomic_id">{{ trans('cruds.user.fields.playtomic_id') }}</label>
                    <input class="form-control" type="text" name="playtomic_id" id="playtomic_id" wire:model.defer="user.playtomic_id">
                    <div class="validation-message">
                        {{ $errors->first('user.playtomic_id') }}
                    </div>
                    <div class="help-block">
                        {{ trans('cruds.user.fields.playtomic_id_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('user.playtomic_password') ? 'invalid' : '' }}">
                    <label class="form-label required" for="playtomic_password">{{ trans('cruds.user.fields.playtomic_password') }}</label>
                    <input class="form-control" type="playtomic_password" name="playtomic_password" id="playtomic_password" required wire:model.defer="playtomic_password">
                    <div class="validation-message">
                        {{ $errors->first('user.playtomic_password') }}
                    </div>
                    <div class="help-block">
                        {{ trans('cruds.user.fields.playtomic_password_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('user.playtomic_token') ? 'invalid' : '' }}">
                    <label class="form-label" for="playtomic_token">{{ trans('cruds.user.fields.playtomic_token') }}</label>
                    <input class="form-control" type="text" name="playtomic_token" id="playtomic_token" wire:model.defer="user.playtomic_token">
                    <div class="validation-message">
                        {{ $errors->first('user.playtomic_token') }}
                    </div>
                    <div class="help-block">
                        {{ trans('cruds.user.fields.playtomic_token_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('user.playtomic_refresh_token') ? 'invalid' : '' }}">
                    <label class="form-label" for="playtomic_refresh_token">{{ trans('cruds.user.fields.playtomic_refresh_token') }}</label>
                    <input class="form-control" type="text" name="playtomic_refresh_token" id="playtomic_refresh_token" wire:model.defer="user.playtomic_refresh_token">
                    <div class="validation-message">
                        {{ $errors->first('user.playtomic_refresh_token') }}
                    </div>
                    <div class="help-block">
                        {{ trans('cruds.user.fields.playtomic_token_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('roles') ? 'invalid' : '' }}">
                    <label class="form-label required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                    <x-select-list class="form-control" required id="roles" name="roles" wire:model="roles" :options="$this->listsForFields['roles']" multiple />
                    <div class="validation-message">
                        {{ $errors->first('roles') }}
                    </div>
                    <div class="help-block">
                        {{ trans('cruds.user.fields.roles_helper') }}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ trans('global.cancel') }}</a>
                    <button class="btn btn-primary mr-2 float-right" type="submit">{{ trans('global.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
