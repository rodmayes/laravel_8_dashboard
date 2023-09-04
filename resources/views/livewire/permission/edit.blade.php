<div class="row">
    <div class="card card-success col-8">
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="card-body">
                <div class="form-group {{ $errors->has('permission.title') ? 'invalid' : '' }}">
                    <label class="form-label required" for="title">{{ trans('cruds.permission.fields.title') }}</label>
                    <input class="form-control" type="text" name="title" id="title" required wire:model.defer="permission.title">
                    <div class="validation-message">
                        {{ $errors->first('permission.title') }}
                    </div>
                    <div class="help-block">
                        {{ trans('cruds.permission.fields.title_helper') }}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group">
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">{{ trans('global.cancel') }}</a>
                    <button class="btn btn-primary mr-2 float-right" type="submit">{{ trans('global.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
