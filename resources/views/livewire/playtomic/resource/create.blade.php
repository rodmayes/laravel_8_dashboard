<div class="row">
    <div class="w-2/3 p-4 border border-gray-200 bg-gray-50 rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
        <h1 class="h3">Create Resource</h1>
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="w-full mb-2">
                <div class="{{ $errors->has('booking.club_id') ? 'invalid' : '' }}">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="club_id">{{ trans('playtomic.bookings.fields.club') }}</label>
                    <x-select
                        placeholder="Select one club"
                        :options="$this->listsForFields['club']"
                        wire:model="resource.club_id"
                        option-value="id"
                        option-label="name"
                    />
                    <small class="text-danger">
                        {{ $errors->first('booking.club_id') }}
                    </small>
                    <div class="help-block">
                        {{ trans('playtomic.bookings.fields.club_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('resource.name') ? 'invalid' : '' }}">
                    <label class="form-label required" for="name">{{ trans('playtomic.resources.fields.name') }}</label>
                    <x-input  name="name" id="name" required wire:model.defer="resource.name"/>
                    <div class="validation-message">
                        {{ $errors->first('resource.name') }}
                    </div>
                    <div class="help-block">
                        {{ trans('playtomic.resources.fields.name_helper') }}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('resource.playtomic_id') ? 'invalid' : '' }}">
                    <label class="form-label required" for="playtomic_id">{{ trans('playtomic.resources.fields.playtomic_id') }}</label>
                    <x-input  name="playtomic_id" id="playtomic_id" required wire:model.defer="resource.playtomic_id"/>
                    <div class="validation-message">
                        {{ $errors->first('resource.playtomic_id') }}
                    </div>
                    <div class="help-block">
                        {{ trans('playtomic.resources.fields.playtomic_id_helper') }}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('resource.priority') ? 'invalid' : '' }}">
                    <label class="form-label required" for="priority">{{ trans('playtomic.resources.fields.priority') }}</label>
                    <x-input  name="priority" id="priority" required wire:model.defer="resource.priority"/>
                    <div class="validation-message">
                        {{ $errors->first('resource.priority') }}
                    </div>
                    <div class="help-block">
                        {{ trans('playtomic.resources.fields.priority_helper') }}
                    </div>
                </div>
            </div>
            <div class="w-full items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="submit" class="float-right text-white bg-green-700 hover:bg-green-200 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    {{ trans('global.save') }}
                </button>
                <a href="{{ route('playtomic.resources.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    {{ trans('global.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
