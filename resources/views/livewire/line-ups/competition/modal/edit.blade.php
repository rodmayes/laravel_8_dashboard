<!-- Modal content -->
<div class="bg-white p-2 sm:px-6 sm:py-4 border-b border-gray-150">
    <!-- Modal header -->
    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ trans('global.edit') }} {{ trans('line-ups.competitions.title') }}
        </h3>
        <button type="button" wire:click="$emit('closeModal')"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>
    </div>
    <!-- Modal body -->
    <form wire:submit.prevent="submit" class="p-4 md:p-5">
        <div class="grid gap-4 mb-4 grid-cols-2">
            <div class="{{ $errors->has('competition.name') ? 'invalid' : '' }}">
                <label class="form-label required" for="name">{{ trans('line-ups.competitions.fields.name') }}</label>
                <x-input name="name" id="name" required wire:model.defer="competition.name"/>
                <div class="text-red-400">
                    {{ $errors->first('competition.name') }}
                </div>
                <div class="help-block">
                    {{ trans('line-ups.competitions.fields.name_helper') }}
                </div>
            </div>
            <div class="{{ $errors->has('competition.year_id') ? 'invalid' : '' }}">
                <label class="form-label required" for="round.team_id">{{ trans('line-ups.competitions.fields.year') }}</label>
                <x-select
                    placeholder="Select year"
                    :options="$this->listsForFields['years']"
                    wire:model.defer="competition.year_id"
                    option-value="id"
                    option-label="name"
                />
                <small class="text-red-400">
                    {{ $errors->first('competition.year_id') }}
                </small>
                <div class="help-block">
                    {{ trans('line-ups.competitions.fields.year_helper') }}
                </div>
            </div>
            <div class="w-full mb-2">
                <livewire:components.input-number :number="$competition->couples_number" :label="trans('line-ups.competitions.fields.couples_number')"></livewire:components.input-number>
            </div>
            <div class="w-full mb-2">
                <div class="{{ $errors->has('competition.couples_number') ? 'invalid' : '' }}">
                    <label class="form-label required" for="couples_number">{{ trans('line-ups.competitions.fields.couples_number') }}</label>
                    <x-input name="couples_number" id="couples_number" required wire:model.defer="competition.couples_number" type="number"/>
                    <div class="text-red-400">
                        {{ $errors->first('competition.couples_number') }}
                    </div>
                    <div class="help-block">
                        {{ trans('line-ups.competitions.fields.couples_number_helper') }}
                    </div>
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
