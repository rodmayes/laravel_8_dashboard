<div class="card">
    <div wire:loading.delay class="col-12 alert alert-info">
        {{trans('global.datatables.loading')}}...
    </div>
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
                <button type="button" wire:click="$emit('openModal','line-ups.configuration.create')"
                        class="btn bg-indigo-700 hover:bg-white text-white hover:text-indigo-700 py-2 px-4 mr-2 border rounded">
                    <i class="fa fa-plus-circle"></i> {{ trans('global.create') }} {{ trans('line-ups.configuration.title') }}
                </button>
                <button wire:click="$emit('$refresh')" data-toggle="tooltip" data-placement="bottom" title="Refresh data"
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
                    {{ trans('line-ups.configuration.fields.id') }}
                    @include('components.table.sort', ['field' => 'id'])
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('line-ups.configuration.fields.name') }}
                    @include('components.table.sort', ['field' => 'name'])
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('line-ups.configuration.fields.value') }}
                </th>
                <th scope="col" class="px-6 py-3">
                </th>
            </tr>
        </thead>
        <tbody class="text-left text-gray-600">
            @forelse($configurations as $configuration)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input type="checkbox" value="{{ $configuration->id }}" wire:model="selected" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                    </td>
                    <td class="px-2 py-2">{{ $configuration->id }}</td>
                    <td class="px-2 py-2 lowercase">{{ $configuration->name }}</td>
                    <td class="px-2 py-2 lowercase">{{ $configuration->value }}</td>
                    <td class="px-2 py-2">
                        <div class="flex justify-end">
                            @can('line-ups.configuration_show')
                                <button data-modal-target="defaultModal" data-modal-toggle="defaultModal" wire:click="showItem({{ $configuration}})" wire:loading.attr="disabled"
                                        class="px-2 py-2 text-xs text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg mr-1 dark:focus:ring-yellow-900">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @endcan
                            @can('line-ups.configuration_edit')
                                <button type="button" wire:click="$emit('openModal','line-ups.configuration.edit', {{ json_encode([$configuration->id]) }})"
                                        class="px-2 py-2 text-xs text-white bg-teal-400 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg mr-1 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endcan
                            @can('line-ups.configuration_delete')
                                <button wire:click="confirmDelete({{ $configuration->id }})" wire:loading.attr="disabled" title="{{ trans('global.delete') }}"
                                        class="px-2.5 py-2 text-xs text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">
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
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> <span class="font-semibold text-gray-900 dark:text-white">{{$configurations->currentPage()}}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $configurations->total() }}</span></span>
            @if($this->selectedCount)
                <p class="text-sm leading-5">
                    {{ $this->selectedCount }}
                    {{ __('Entries selected') }}
                </p>
            @endif
            <ul class="inline-flex -space-x-px text-sm h-8">
                <li>
                    <a href="{{$configurations->previousPageUrl()}}" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                </li>
                @foreach($configurations->getUrlRange(1,ceil($configurations->total()/$configurations->perPage())) as $index => $page)
                    <li>
                        <a href="{{$configurations->url($index)}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{$index}}</a>
                    </li>
                @endforeach
                <li>
                    <a href="{{$configurations->nextPageUrl()}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main modal -->
    <div id="defaultModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full" wire:ignore.self>
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $selected_item->id ?: null }}
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="defaultModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('line-ups.years.fields.id') }}: </label>
                        {{ $selected_item->id ?: null }}
                    </div>
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('line-ups.years.fields.name') }}: </label>
                        {{ $selected_item->name ?: null }}
                    </div>
                    <div>
                        <label for="couples_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ trans('line-ups.years.fields.couples_number') }}: </label>
                        {{ $selected_item->couples_number ?: null }}
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <a href="{{ route('line-ups.administration.configuration.show', $selected_item) }}"
                       type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        {{ trans('global.edit') }}
                    </a>
                    <button data-modal-hide="defaultModal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        {{ trans('global.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

@endpush
