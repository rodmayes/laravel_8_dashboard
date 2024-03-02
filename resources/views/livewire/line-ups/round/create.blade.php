<div class="row">
    <div class="w-2/3 p-4 border border-gray-200 bg-gray-50 rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
        <h1 class="h3">{{ trans('global.create') }} {{ trans('line-ups.rounds.title') }}</h1>
        @error('error') <small class="text-danger">{{ $message }}</small> @enderror
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="{{ $errors->has('round.competition_id') ? 'invalid' : '' }}">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="round.competition_id">{{ trans('line-ups.round.fields.competition') }}</label>
                <x-select
                    placeholder="Select competition"
                    :options="$this->listsForFields['competitions']"
                    wire:model.defer="round.competition_id"
                    option-value="id"
                    option-label="name"
                />
                <small class="text-red-400">
                    {{ $errors->first('round.competition_id') }}
                </small>
                <div class="help-block">
                    {{ trans('line-ups.round.fields.competition_helper') }}
                </div>
            </div>
            <div class="{{ $errors->has('round.team_id') ? 'invalid' : '' }}">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="round.team_id">{{ trans('line-ups.round.fields.team') }}</label>
                <x-select
                    placeholder="Select competition"
                    :options="$this->listsForFields['teams']"
                    wire:model.defer="round.team_id"
                    option-value="id"
                    option-label="name"
                />
                <small class="text-red-400">
                    {{ $errors->first('round.team_id') }}
                </small>
                <div class="help-block">
                    {{ trans('line-ups.round.fields.team_helper') }}
                </div>
            </div>
            <div class="w-full mb-2">
                <div class="{{ $errors->has('round.round_number') ? 'invalid' : '' }}">
                    <label class="form-label required" for="round_number">{{ trans('line-ups.rounds.fields.round_number') }}</label>
                    <x-input name="round_number" id="round_number" required wire:model.defer="round.round_number" type="number"/>
                    <div class="text-red-400">
                        {{ $errors->first('round.round_number') }}
                    </div>
                    <div class="help-block">
                        {{ trans('line-ups.rounds.fields.round_number_helper') }}
                    </div>
                </div>
            </div>
            <div class="mr-5 {{ $errors->has('round.match_day') ? 'is-invalid' : '' }}">
                <label class="form-label required" for="round.match_day">{{ trans('line-ups.line-ups.fields.match_day') }}</label>
                <div inline-datepicker datepicker-buttons data-date="{{ isset($round->match_day) ? $round->match_day->format('d-m-Y') : now()->format('d-m-Y') }}"
                     datepicker-format="dd-mm-yyyy" wire:model="round.match_day" required wire:ignore id="match_day" week-start="1"></div>
                <small class="text-danger">
                    {{ $errors->first('round.match_day') }}
                </small>
                <div class="help-block">
                    {{ trans('line-ups.line-ups.fields.match_day_helper') }}
                </div>
            </div>
            <div class="w-full items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="submit" class="float-right text-white bg-teal-400 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    {{ trans('global.save') }}
                </button>
                <a href="{{ route('line-ups.administration.round.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    {{ trans('global.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        const datepickerEl = document.getElementById('match_day');

        datepickerEl.addEventListener('changeDate', (event) => {
            window.livewire.emit('dateSelected', event.detail.date);
        });
    </script>
@endpush
