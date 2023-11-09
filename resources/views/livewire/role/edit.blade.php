<div class="w-2/3 p-4 border border-gray-200 bg-white rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
    <h1 class="h3">Edit Role</h1>
    <form wire:submit.prevent="submit" class="pt-3">
        <div class="w-full">
            <div class="{{ $errors->has('role.title') ? 'invalid' : '' }}">
                <label class="form-label required" for="title">{{ trans('cruds.role.fields.title') }}</label>
                <x-input name="title" id="title" required wire:model.defer="role.title"/>
                <div class="validation-message">
                    {{ $errors->first('role.title') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.role.fields.title_helper') }}
                </div>
            </div>
            <div class="{{ $errors->has('permissions') ? 'invalid' : '' }}">
                <label class="form-label required" for="permissions">{{ trans('cruds.role.fields.permissions') }}</label>
                <x-select
                    placeholder="Select many permissions"
                    multiselect
                    :options="$this->listsForFields['permissions']"
                    wire:model.defer="permissions"
                    option-value="id"
                    option-label="title"
                />
                <div class="validation-message">
                    {{ $errors->first('permissions') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.role.fields.permissions_helper') }}
                </div>
            </div>
        </div>
        <div class="w-full items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
            <button type="submit" class="float-right text-white bg-teal-400 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{ trans('global.save') }}
            </button>
            <a href="{{ route('admin.roles.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.cancel') }}
            </a>
        </div>
    </form>
</div>
