<div class="bg-gray-100 flex-1 p-6 md:mt-16">
    @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex justify-center items-center mb-2">
        <div class="inline-flex rounded-md shadow-sm" role="group">
            @can('line-ups.year_access')
                <button type="button" wire:click="$set('active_page', 'years')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                    <i class="fa fa-calendar mr-2"></i>
                    {{ __('line-ups.years.title_plural') }}
                </button>
            @endcan
            @can('line-ups.competition_access')
                <button type="button" wire:click="$set('active_page', 'competitions')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                    <i class="fa fa-trophy mr-2"></i>
                    {{ __('line-ups.competitions.title_plural') }}
                </button>
            @endcan
            @can('line-ups.team_access')
                <button type="button" wire:click="$set('active_page', 'teams')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                    <i class="fa fa-users-cog mr-2"></i>
                    {{ __('line-ups.teams.title_plural') }}
                </button>
            @endcan
            @can('line-ups.player_access')
                <button type="button" wire:click="$set('active_page', 'players')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                    <i class="fa fa-users mr-2"></i>
                    {{ __('line-ups.players.title_plural') }}
                </button>
            @endcan
            @can('line-ups.configuration_access')
                <button type="button" wire:click="$set('active_page', 'configuration')"
                        class="iinline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                    <i class="fa fa-cog mr-2"></i>
                    {{ __('line-ups.configuration.title') }}
                </button>
            @endcan
        </div>
    </div>

    @if($active_page === 'years')
        <h1 class="h5 mb-2">{{ __('line-ups.years.title_plural') }} List</h1>
        @livewire('line-ups.year.index', ['year' => $data['year']])
    @elseif($active_page === 'teams')
        <h1 class="h5 mb-2">{{ __('line-ups.teams.title_plural') }} List</h1>
        @livewire('line-ups.team.index')
    @elseif($active_page === 'competitions')
        <h1 class="h5 mb-2">{{ __('line-ups.competitions.title_plural') }} List</h1>
        @livewire('line-ups.competition.index')
    @elseif($active_page === 'players')
        <h1 class="h5 mb-2">{{ __('line-ups.players.title_plural') }} List</h1>
        @livewire('line-ups.player.index')
    @elseif($active_page === 'configuration')
        <h1 class="h5 mb-2">{{ __('line-ups.configuration.title_plural') }}</h1>
        @livewire('line-ups.configuration.index')
    @endif
</div>

@push('scripts')
    <script>
        window.livewire.on('close-modals', (modald) => {
            //$("button[data-modal-hide='"+modald+"']").modal('hide');
        })
    </script>

    <script>
        window.livewire.on('close-modals', (modald) => {
            $("button[data-modal-hide='"+modald+"']").click();
        })
    </script>
@endpush
