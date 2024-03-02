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
                        <select wire:model="perPage" class="block appearance-none w-full  border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
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
                               class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" />
                    </div>
                </div>
            </div>
            <div class="flex flex-row-reverse px-4 py-2 m-2">
                <button class="bg-red-500 hover:bg-white text-white hover:text-red-500 py-2 px-4 mr-2 border rounded" wire:click="confirmMassive" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    <i class="fa fa-trash"></i> {{ __('Delete Selected') }}
                </button>
                <button type="button" wire:click="$emit('openModal','line-ups.player.create')"
                        class="btn bg-indigo-700 hover:bg-white text-white hover:text-indigo-700 py-2 px-4 mr-2 border rounded">
                    <i class="fa fa-plus-circle"></i> {{ trans('global.create') }} {{ trans('line-ups.players.title') }}
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
                        <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-all-search" class="sr-only">checkbox</label>
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('line-ups.players.fields.id') }}
                    @include('components.table.sort', ['field' => 'id'])
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('line-ups.players.fields.name') }}
                    @include('components.table.sort', ['field' => 'name'])
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('line-ups.players.fields.email') }}
                    @include('components.table.sort', ['field' => 'email'])
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('line-ups.players.fields.position') }}
                    @include('components.table.sort', ['field' => 'position'])
                </th>
                <th scope="col" class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="text-left text-gray-600">
            @forelse($players as $player)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input type="checkbox" value="{{ $player->id }}" wire:model="selected" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                    </td>
                    <td class="px-2 py-2">{{ $player->id }}</td>
                    <td class="px-2 py-2">{{ $player->name }}</td>
                    <td class="px-2 py-2">{{ $player->email }}</td>
                    <td class="px-2 py-2">{{ $player->position }}</td>
                    <td class="px-2 py-2">
                        <div class="flex justify-end">
                            @can('line-ups.player_show')
                                <button data-modal-target="lineups-player-show" data-modal-toggle="lineups-player-show"
                                        wire:click="showItem({{ $player }})" wire:loading.attr="disabled"
                                        class="px-2 py-2 text-xs text-white mr-1 bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg dark:focus:ring-yellow-900">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @endcan
                            @can('line-ups.player_edit')
                                <button type="button" wire:click="$emit('openModal','line-ups.player.edit', {{ json_encode([$player->id]) }})"
                                        class="px-2 py-2 text-xs text-white bg-teal-400 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg mr-1 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endcan
                            @can('line-ups.player_delete')
                                <button
                                    class="px-2.5 py-2 text-xs text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800"
                                    wire:click="confirmDelete({{ $player->id }})" wire:loading.attr="disabled"
                                    title="{{ trans('global.delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5 pl-4">{{trans('global.datatables.no_items_found')}}</td>
                </tr>
            @endforelse
        </tbody>
        </table>
        <nav class="flex items-center justify-between p-4 " aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> <span class="font-semibold text-gray-900 dark:text-white">{{$players->currentPage()}}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $players->total() }}</span></span>
            @if($this->selectedCount)
                <p class="text-sm leading-5">
                    {{ $this->selectedCount }}
                    {{ __('Entries selected') }}
                </p>
            @endif
            <ul class="inline-flex -space-x-px text-sm h-8">
                <li>
                    <a href="{{$players->previousPageUrl()}}" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                </li>
                @foreach($players->getUrlRange(1,ceil($players->total()/$players->perPage())) as $index => $page)
                    <li>
                        <a href="{{$players->url($index)}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{$index}}</a>
                    </li>
                @endforeach
                <li>
                    <a href="{{$players->nextPageUrl()}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                </li>
            </ul>
        </nav>
        @include('livewire.line-ups.player.modal.show')
    </div>
</div>

@push('scripts')

@endpush
