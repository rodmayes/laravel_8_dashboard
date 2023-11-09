<div class="w-2/3 p-4 border border-gray-200 bg-white rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
    <h1 class="h3">Edit Timetable</h1>
    <form wire:submit.prevent="submit" class="pt-3">
        <div class="w-1/ mb-2">
            <div class="{{ $errors->has('timetable.name') ? 'invalid' : '' }}">
                <label class="form-label required" for="name">{{ trans('playtomic.timetable.fields.name') }}</label>
                <x-input  name="name" id="name" required wire:model.defer="timetable.name"/>
                <div class="validation-message">
                    {{ $errors->first('timetable.name') }}
                </div>
                <div class="help-block">
                    {{ trans('playtomic.timetable.fields.name_helper') }}
                </div>
            </div>

            <div class="grid lg:grid-cols-2 md:grid-cols-2 xs:grid-cols-1 xl:grid-cols-1">
                <div class="mr-2">
                    <div class="{{ $errors->has('timetable.playtomic_id') ? 'invalid' : '' }}">
                        <label class="form-label required" for="playtomic_id">{{ trans('playtomic.timetable.fields.playtomic_id') }}</label>
                        <x-input  name="playtomic_id" id="playtomic_id" required wire:model.defer="timetable.playtomic_id"/>
                        <div class="validation-message">
                            {{ $errors->first('timetable.playtomic_id') }}
                        </div>
                        <div class="help-block">
                            {{ trans('playtomic.timetable.fields.playtomic_id_helper') }}
                        </div>
                    </div>
                </div>
                <div class="pt-2>
                     <div class="{{ $errors->has('timetable.playtomic_id') ? 'invalid' : '' }}">
                <label class="form-label required" for="playtomic_id">{{ trans('playtomic.timetable.fields.playtomic_id') }}</label>
                <x-input  name="playtomic_id" id="playtomic_id" required wire:model.defer="timetable.playtomic_id"/>
                <div class="validation-message">
                    {{ $errors->first('timetable.playtomic_id') }}
                </div>
                <div class="help-block">
                    {{ trans('playtomic.timetable.fields.playtomic_id_summer_helper') }}
                </div>
            </div>
        </div>
        <div class="w-full items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
            <button type="submit" class="float-right text-white bg-teal-400 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{ trans('global.save') }}
            </button>
            <a href="{{ route('playtomic.timetables.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.cancel') }}
            </a>
        </div>
    </form>
</div>
