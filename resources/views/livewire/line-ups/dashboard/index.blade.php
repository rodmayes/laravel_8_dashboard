<div class="bg-gray-100 flex-1 p-6 md:mt-16">
    @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex justify-end mb-2 items-center shadow-xs transition-all duration-300 ease-in-out p-5 hover:shadow-md bg-gray-300">
        <h1 class="ml-3 mr-2">{{ __('line-ups.dashboard.active_year') }}</h1>
        <select wire:model="active_year" class="block mr-2 appearance-none  border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
            @foreach($data['years'] as $year)
                <option value="{{ $year->id }}">{{ $year->name }}</option>
            @endforeach
        </select>
        <button class="btn btn-shadow" wire:click="setActiveYear">{{ __('line-ups.dashboard.set_active_year') }}</button>
    </div>

    <div class="grid grid-cols-4 gap-6 xl:grid-cols-1">

        <!-- card -->
        <div class="report-card">
            <div class="card">
                <div class="card-body flex flex-col">

                    <!-- top -->
                    <div class="flex flex-row justify-between items-center">
                        <div class="h6 text-indigo-700 fad fa-globe"></div>
                        <a class="btn bg-indigo-500 text-sm text-white" href="{{ route('line-ups.administration.year.index')  }}">{{ __('line-ups.go') }}</a>
                    </div>
                    <!-- end top -->

                    <!-- bottom -->
                    <div class="mt-8">
                        <h1 class="h5">{{ $data['year'] ? $data['year']->name : 'No year' }}</h1>
                        <p>{{ trans('line-ups.years.title') }}</p>
                    </div>
                    <!-- end bottom -->

                </div>
            </div>
            <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
        </div>
        <!-- end card -->

        <!-- card -->
        <div class="report-card">
            <div class="card">
                <div class="card-body flex flex-col">

                    <!-- top -->
                    <div class="flex flex-row justify-between items-center">
                        <div class="h6 text-red-700 fad fa-trophy"></div>
                        <a class="btn bg-red-500 text-sm text-white" href="{{ route('line-ups.administration.competition.index')  }}">{{ __('line-ups.go') }}</a>
                    </div>
                    <!-- end top -->

                    <!-- bottom -->
                    <div class="mt-8">
                        <h1 class="h5">{{ $data['competitions']->count() }}</h1>
                        <p>{{ trans('line-ups.competitions.title_plural') }}</p>
                    </div>
                    <!-- end bottom -->

                </div>
            </div>
            <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
        </div>
        <!-- end card -->


        <!-- card -->
        <div class="report-card">
            <div class="card">
                <div class="card-body flex flex-col">

                    <!-- top -->
                    <div class="flex flex-row justify-between items-center">
                        <div class="h6 text-yellow-600 fad fa-object-group"></div>
                        <a class="btn bg-yellow-500 text-sm text-white" href="{{ route('line-ups.administration.team.index')  }}">{{ __('line-ups.go') }}</a>
                    </div>
                    <!-- end top -->

                    <!-- bottom -->
                    <div class="mt-8">
                        <h1 class="h5">{{ $data['teams']->count() }}</h1>
                        <p>{{ trans('line-ups.teams.title_plural') }}</p>
                    </div>
                    <!-- end bottom -->

                </div>
            </div>
            <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
        </div>
        <!-- end card -->

        <!-- card -->
        <div class="report-card">
            <div class="card">
                <div class="card-body flex flex-col">

                    <!-- top -->
                    <div class="flex flex-row justify-between items-center">
                        <div class="h6 text-green-700 fad fa-users"></div>
                        <a class="btn bg-green-500 text-sm text-white" href="{{ route('line-ups.administration.player.index')  }}">{{ __('line-ups.go') }}</a>
                    </div>
                    <!-- end top -->

                    <!-- bottom -->
                    <div class="mt-8">
                        <h1 class="h5">{{ $data['players']->count() }}</h1>
                        <p>{{ trans('line-ups.players.title_plural') }}</p>
                    </div>
                    <!-- end bottom -->

                </div>
            </div>
            <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
        </div>
        <!-- end card -->
    </div>
</div>
