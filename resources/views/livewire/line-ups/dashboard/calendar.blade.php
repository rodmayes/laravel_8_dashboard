<div>
    <div class="card">
        <div class="card-header flex flex-row justify-between">
            <h1 class="h6">{{ $year ? $year->name : '' }}</h1>

            <div class="flex flex-row justify-center items-center">
                <a href="">
                    <i class="fad fa-chevron-double-down mr-6"></i>
                </a>
                <a href="">
                    <i class="fad fa-ellipsis-v"></i>
                </a>
            </div>

        </div>
        <div class="card-body grid grid-cols-3 lg:grid-cols-1">
            <div class="flex items-end px-4 py-2 m-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="competitions">{{trans('line-ups.competitions.title_plural')}}:&nbsp;</label>
                <x-select
                    placeholder="{{ trans('line-ups.rounds.select_competition') }}"
                    :options="$this->listsForFields['competitions']"
                    wire:model="competition"
                    option-value="id"
                    option-label="name"

                />
            </div>

            <div class="flex items-end px-4 py-2 m-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white required" for="teams">{{trans('line-ups.teams.title_plural')}}:&nbsp;</label>
                <x-select
                    placeholder="{{ trans('line-ups.rounds.select_team') }}"
                    :options="$this->listsForFields['teams']"
                    wire:model="team"
                    option-value="id"
                    option-label="name"

                />
            </div>

            <div class="flex items-end px-4 py-2 m-2">
                <label class="block mb-2 mr-1 text-sm font-medium text-gray-900 dark:text-white required" for="rounds">{{trans('line-ups.rounds.title_plural')}}:&nbsp;</label>
                <x-select
                    placeholder="{{ trans('line-ups.rounds.select_round') }}"
                    :options="$this->listsForFields['rounds']"
                    wire:model="round"
                    option-value="id"
                    option-label="round_number"
                    class="mr-2"
                />
                <button class="btn btn-indigo flex justify-end" data-modal-target="addRoundModal" data-modal-toggle="addRoundModal">{{ __('line-ups.rounds.add_round') }}</button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab" data-tabs-toggle="#default-styled-tab-content" data-tabs-active-classes="text-purple-600 hover:text-purple-600 dark:text-purple-500 dark:hover:text-purple-500 border-purple-600 dark:border-purple-500" data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300" role="tablist">
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="rounds-styled-tab" data-tabs-target="#tab-rounds" type="button" role="tab" aria-controls="rounds" aria-selected="false">
                            {{ trans('line-ups.rounds.title_plural') }}
                        </button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="players-styled-tab" data-tabs-target="#tab-players" type="button" role="tab" aria-controls="players" aria-selected="false">
                            {{ trans('line-ups.players.title_plural') }}
                        </button>
                    </li>
                </ul>
            </div>
            <div id="default-styled-tab-content">
                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="tab-rounds" role="tabpanel" aria-labelledby="rounds-tab">
                    <div>
                        <livewire:line-ups.dashboard.rounds :year="$year"/>
                    </div>
                </div>
                <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="tab-players" role="tabpanel" aria-labelledby="players-tab">
                    <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Dashboard tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
                </div>
            </div>

        </div>
    </div>
    @include('livewire.line-ups.dashboard.modal.add-round')
</div>
