<!-- Main modal -->
<div id="addRoundModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full" wire:ignore.self>
    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ trans('line-ups.rounds.add_round') }}
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="addRoundModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                @if(!$competition)
                    <div class="alert alert-error">
                        {{ trans('line-ups.rounds.no_competition_selected') }}
                    </div>
                @endif
                @error('error') <small class="text-danger">{{ $message }}</small> @enderror
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('line-ups.competitions.title') }}: </label>
                    {{ $competition ? \App\Models\LineUps\Competition::find($competition)->name : null }}
                </div>
                <div class="mr-5 {{ $errors->has('match_day') ? 'is-invalid' : '' }}">
                    <label class="form-label required" for="started_at">{{ trans('line-ups.rounds.fields.match_day') }}</label>
                    <div inline-datepicker datepicker-buttons datepicker-autoselect-today data-date="{{ isset($match_day) ? $match_day->format('d-m-Y') : ($start_date ?? now()->format('d-m-Y')) }}"
                         datepicker-format="dd-mm-yyyy" wire:model="match_day" required wire:ignore id="started_at" weekStart="3"></div>
                    <small class="text-danger">
                        {{ $errors->first('match_day') }}
                    </small>
                    <div class="help-block">
                        {{ trans('line-ups.rounds.fields.match_day_helper') }}
                    </div>
                </div>

            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                @if($competition)
                    <button wire:click="addRound"
                       type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        {{ trans('global.add') }}
                    </button>
                @endif
                <button data-modal-hide="addRoundModal" type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    {{ trans('global.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const datepickerEl = document.getElementById('started_at');

        datepickerEl.addEventListener('changeDate', (event) => {
            window.livewire.emit('dateSelected', event.detail.date);
        });
    </script>
@endpush
