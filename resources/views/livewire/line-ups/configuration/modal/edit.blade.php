<div class="row">
    <div class="w-2/3 p-4 border border-gray-200 bg-gray-50 rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
        <h1 class="h3">{{ trans('global.create') }} {{ trans('line-ups.configuration.title') }}</h1>
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="w-full mb-2">
                <div class="{{ $errors->has('configuration.name') ? 'invalid' : '' }}">
                    <label class="form-label required" for="name">{{ trans('line-ups.competitions.fields.name') }}</label>
                    <x-input name="name" id="name" required wire:model.defer="configuration.name"/>
                    <div class="text-red-400">
                        {{ $errors->first('configuration.name') }}
                    </div>
                    <div class="help-block">
                        {{ trans('line-ups.configurations.fields.name_helper') }}
                    </div>
                </div>
            </div>
            <div class="w-full mb-2">
                <div class="{{ $errors->has('configuration.value') ? 'invalid' : '' }}">
                    <label class="form-label required" for="value">{{ trans('line-ups.configurations.fields.value') }}</label>
                    <x-input name="value" id="value" required wire:model.defer="configuration.value"/>
                    <div class="text-red-400">
                        {{ $errors->first('configuration.value') }}
                    </div>
                    <div class="help-block">
                        {{ trans('line-ups.configurations.fields.value_helper') }}
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-between items-center pt-4 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="button" wire:click="$emit('closeModal')"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    {{ trans('global.cancel') }}
                </button>
                <button type="submit" class="btn focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
