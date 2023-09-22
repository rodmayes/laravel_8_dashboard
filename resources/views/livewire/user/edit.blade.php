<div class="row">
    <div class="card card-success col-8">
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="card-body">
                <div class="row">
                    <h3 class="col-12">
                        App data
                        <button class="btn btn-info float-right" wire:click="refreshData" type="button"><i class="fa fa-sync" title="Refresh Playtomic Token"></i> Refresh Data</button>
                        <div wire:loading wire:target="refreshData">
                            Processing Refresh...
                        </div>
                    </h3>
                </div>
                <div class="row">
                    <div class="form-group {{ $errors->has('user.name') ? 'invalid' : '' }} col-4">
                        <label class="form-label required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                        <input class="form-control" type="text" name="name" id="name" required wire:model.defer="user.name">
                        <div class="text-danger">
                            {{ $errors->first('user.name') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.name_helper') }}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('user.email') ? 'invalid' : '' }} col-4">
                        <label class="form-label required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                        <input class="form-control" type="email" name="email" id="email" required wire:model.defer="user.email">
                        <div class="text-danger">
                            {{ $errors->first('user.email') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.email_helper') }}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('user.password') ? 'invalid' : '' }} col-4">
                        <label class="form-label" for="password">{{ trans('cruds.user.fields.password') }}</label>
                        <div class="input-group">
                            <input class="form-control" type="password" name="password" id="password" wire:model.defer="password" value="">
                            <div class="input-group-append">
                                <button class="btn btn-warning" wire:click="storePassword" type="button"><i class="fa fa-save" title="Save password"></i></button>
                            </div>
                        </div>
                        <div class="text-danger">
                            {{ $errors->first('user.password') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.password_helper') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group {{ $errors->has('roles') ? 'invalid' : '' }} col-12">
                        <label class="form-label required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                        <x-select-list class="form-control" required id="roles" name="roles" :options="$this->listsForFields['roles']" wire:model="roles" multiple/>
                        <div class="text-danger">
                            {{ $errors->first('roles') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.roles_helper') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h3 class="col-12">
                        Playtomic data
                        <button class="btn btn-danger btn-sm float-right" wire:click="refreshToken" type="button"><i class="fa fa-sync" title="Refresh Playtomic Token"></i> Refresh Playtomic Token</button>
                        <div wire:loading wire:target="refreshToken">
                            Processing Refresh...
                        </div>

                    </h3>
                </div>
                <div class="row">
                    <div class="form-group {{ $errors->has('user.playtomic_id') ? 'invalid' : '' }} col-2">
                        <label class="form-label" for="playtomic_id">{{ trans('playtomic.clubs.fields.playtomic_id') }}</label>
                        <input class="form-control" type="text" name="playtomic_id" id="playtomic_id" wire:model.defer="user.playtomic_id">
                        <div class="text-danger">
                            {{ $errors->first('user.playtomic_id') }}
                        </div>
                        <div class="help-block">
                            {{ trans('playtomic.clubs.fields.playtomic_id_helper') }}
                        </div>
                    </div>
                    <div class="form-group col-6 {{ $errors->has('user.playtomic_password') ? 'invalid' : '' }}">
                        <label class="form-label required" for="playtomic_password">{{ trans('cruds.user.fields.playtomic_password') }}</label>
                        <div class="input-group">
                            <input class="form-control" type="playtomic_password" name="playtomic_password" id="playtomic_password" wire:model.defer="playtomic_password">
                            <div class="input-group-append">
                                <button class="btn btn-warning" wire:click="storePlaytomicPassword" type="button">
                                    <i class="fa fa-save" title="Save Playtomic password"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-danger">
                            {{ $errors->first('user.playtomic_password') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.playtomic_password_helper') }}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('user.playtomic_token') ? 'invalid' : '' }} col-12">
                        <label class="form-label" for="playtomic_token">{{ trans('cruds.user.fields.playtomic_token') }}</label>
                        <p>{{ $this->user->playtomic_token }}</p>
                        <div class="text-danger">
                            {{ $errors->first('user.playtomic_token') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.playtomic_token_helper') }}
                        </div>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('user.playtomic_refresh_token') ? 'invalid' : '' }}">
                    <label class="form-label" for="playtomic_refresh_token">{{ trans('cruds.user.fields.playtomic_refresh_token') }}</label>
                    <p>{{ $this->user->playtomic_refresh_token }}</p>
                    <div class="text-danger">
                        {{ $errors->first('user.playtomic_refresh_token') }}
                    </div>
                    <div class="help-block">
                        {{ trans('cruds.user.fields.playtomic_token_helper') }}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ trans('global.cancel') }}</a>
                        <button class="btn btn-primary mr-2 float-right" type="submit">{{ trans('global.save') }}</button>
                    </div>
                </div>
            </div>
        </form>
</div>


<script type="text/javascript">
    window.onload = (event) => {

    };
</script>
