<div class="card">
    @include('livewire.components.loading')

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex items-center justify-between bg-white dark:bg-gray-900">
            <div class="flex">
                <div class="flex-initial px-4 py-2 m-2">
                    <div>
                        <label class="block text-gray-700 font-bold md:text-right mb-1 md:mb-0 pr-4" for="perPage">
                            {{trans('global.datatables.per_page')}}:
                        </label>
                        <select wire:model="perPage"
                                class="block appearance-none w-full  border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                            @foreach($paginationOptions as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex-initial px-4 py-2 m-2">
                    <div>
                        <label class="block text-gray-700 font-bold md:text-right mb-1 md:mb-0 pr-4" for="perPage">
                            Search:
                        </label>
                        <input type="text" id="table-search" wire:model.debounce.300ms="search" placeholder="Search"
                               class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"/>
                    </div>
                </div>
            </div>
            <div class="flex flex-row-reverse px-4 py-2 m-2">
                <button class="bg-red-500 hover:bg-white text-white hover:text-red-500 py-2 px-4 mr-2 border rounded" wire:click="confirmMassive" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    <i class="fa fa-trash"></i> {{ __('Delete Selected') }}
                </button>
                <button type="button" wire:click="$emit('openModal','line-ups.team.create')"
                        class="btn bg-indigo-700 hover:bg-white text-white hover:text-indigo-700 py-2 px-4 mr-2 border rounded">
                    <i class="fa fa-plus-circle"></i> {{ trans('global.create') }} {{ trans('line-ups.teams.title') }}
                </button>
                <button wire:click="$emit('item-updated')" data-toggle="tooltip" data-placement="bottom" title="Refresh data"
                        class="bg-emerald-500 hover:bg-white text-white hover:text-black py-2 px-4 mr-2 border rounded">
                    <i class="fas fa-sync"></i>
                </button>
            </div>
        </div>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="checkbox-all-search" type="checkbox"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-all-search" class="sr-only">checkbox</label>
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('line-ups.teams.fields.id') }}
                    @include('components.table.sort', ['field' => 'id'])
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('line-ups.teams.fields.name') }}
                    @include('components.table.sort', ['field' => 'name'])
                </th>
                <th scope="col" class="px-6 py-3">Data</th>
                <th scope="col" class="px-6 py-3"></th>
            </tr>
            </thead>
            <tbody class="text-left text-gray-600">
            @forelse($teams as $team)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input type="checkbox" value="{{ $team->id }}" wire:model="selected"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                    </td>
                    <td class="px-2 py-2">{{ $team->id }}</td>
                    <td class="px-2 py-2">{{ $team->name }}</td>
                    <td class="px-2 py-2">
                        <span class="rounded-full text-white badge bg-teal-400 text-xs mr-2 py-1 px-2"
                              data-toggle="tooltip" data-placement="top" title="Years">
                            <i class="fa fa-globe"></i> {{ $team->years->pluck('id')->unique()->count() }}
                        </span>
                        <span class="rounded-full text-white badge bg-teal-400 text-xs py-1 px-2" data-toggle="tooltip"
                              data-placement="top" title="Competitions">
                            <i class="fa fa-trophy"></i> {{ $team->competitions->count() }}
                        </span>
                    </td>
                    <td class="px-2 py-2">
                        <div class="flex justify-end">
                            @can('line-ups.team_show')
                                <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                                        wire:click="showItem({{ $team }})" wire:loading.attr="disabled"
                                        class="px-2 py-2 text-xs text-white mr-1 bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg dark:focus:ring-yellow-900">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @endcan
                            @can('line-ups.team_edit')
                                <button type="button" wire:click="$emit('openModal','line-ups.team.edit', {{ json_encode([$team->id]) }})"
                                        class="px-2 py-2 text-xs text-white bg-teal-400 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg mr-1 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endcan
                            @can('line-ups.team_delete')
                                <button
                                    class="px-2.5 py-2 text-xs text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800"
                                    wire:click="confirmDelete({{ $team->id }})" wire:loading.attr="disabled"
                                    title="{{ trans('global.delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-2 py-2" colspan="5">{{trans('global.datatables.no_items_found')}}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <nav class="flex items-center justify-between p-4 " aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> <span
                    class="font-semibold text-gray-900 dark:text-white">{{$teams->currentPage()}}</span> of <span
                    class="font-semibold text-gray-900 dark:text-white">{{ $teams->total() }}</span></span>
            @if($this->selectedCount)
                <p class="text-sm leading-5">
                    {{ $this->selectedCount }}
                    {{ __('Entries selected') }}
                </p>
            @endif
            <ul class="inline-flex -space-x-px text-sm h-8">
                <li>
                    <a href="{{$teams->previousPageUrl()}}"
                       class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                </li>
                @foreach($teams->getUrlRange(1,ceil($teams->total()/$teams->perPage())) as $index => $page)
                    <li>
                        <a href="{{$teams->url($index)}}"
                           class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{$index}}</a>
                    </li>
                @endforeach
                <li>
                    <a href="{{$teams->nextPageUrl()}}"
                       class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    @include('livewire.line-ups.team.modal.show')
</div>
