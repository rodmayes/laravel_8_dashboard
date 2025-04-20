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
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded text-sm py-3 px-4 mr-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                    <i class="fas fa-bars"></i>
                </button>
                <!-- Dropdown menu -->
                <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 mr-2">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                        <li>
                            <a class="block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-primary-100 hover:text-gray" href="{{ route('user_management.users.create') }}">
                                <i class="fa fa-plus-circle"></i> {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
                            </a>
                        </li>
                        <li>
                            <button class="block px-4 py-2 text-sm text-red-500 hover:bg-primary-100 hover:text-gray disabled:cursor-not-allowed rounded-md" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                                <i class="fa fa-trash"></i> {{ __('Delete Selected') }}
                            </button>
                        </li>
                    </ul>
                </div>
                <button class="bg-emerald-500 hover:bg-blue-500 text-white  hover:text-white py-2 px-4 mr-2 border rounded" wire:click="$emit('refreshComponent')" data-toggle="tooltip" data-placement="bottom" title="Refresh data">
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
                <th scope="col" class="px-6 py-3"></th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('cruds.user.fields.id') }}
                    @include('components.table.sort', ['field' => 'id'])
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('cruds.user.fields.name') }}
                    @include('components.table.sort', ['field' => 'name'])
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('cruds.user.fields.email') }}
                    @include('components.table.sort', ['field' => 'email'])
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('playtomic.clubs.fields.playtomic_id') }}
                    @include('components.table.sort', ['field' => 'playtomic_id'])
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ trans('cruds.user.fields.roles') }}
                </th>
                <th scope="col" class="px-6 py-3"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input type="checkbox" value="{{ $user->id }}" wire:model="selected" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                    </td>
                    <td>
                        <img src="{{ $user->getAvatar() }}" class="w-8 h-8 object-cover" wire:model="image">
                    </td>
                    <td class="px-2 py-2">{{ $user->id }}</td>
                    <td class="px-2 py-2">{{ $user->name }}</td>
                    <td class="px-2 py-2">
                        <a class="link-light-blue" href="mailto:{{ $user->email }}">
                            <i class="far fa-envelope fa-fw"></i>
                            {{ $user->email }}
                        </a>
                    </td>
                    <td class="px-2 py-2">{{ $user->playtomic_id }}</td>
                    <td class="px-2 py-2">
                        @foreach($user->roles as $key => $entry)
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $entry->title }}</span>
                        @endforeach
                    </td>
                    <td class="px-2 py-2">
                        <div class="inline-flex">
                            @can('user_management.user_show')
                                <a class="px-2 py-2 text-xs text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900" href="{{ route('user_management.users.show', $user) }}" title="{{ trans('global.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endcan
                            @can('user_management.user_edit')
                                <a class="px-2 py-2 text-xs text-white bg-teal-400 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800" href="{{ route('user_management.users.edit', $user) }}" title="{{ trans('global.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan
                            @can('user_management.user_delete')
                                <button class="px-2 py-2 text-xs text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                        wire:click="impersonate({{ $user }})" title="Login as">
                                    <i class="fas fa-user"></i>
                                </button>
                            @endcan
                            @can('user_management.user_delete')
                                <button class="px-2 py-2 text-xs text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800" wire:click="confirmDelete({{ $user->id }})" wire:loading.attr="disabled" title="{{ trans('global.delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No entries found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <nav class="flex items-center justify-between p-4" aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> <span class="font-semibold text-gray-900 dark:text-white">{{$users->currentPage()}}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $users->total() }}</span></span>
            @if($this->selectedCount)
                <p class="text-sm leading-5">
                    {{ $this->selectedCount }}
                    {{ __('Entries selected') }}
                </p>
            @endif
            <ul class="inline-flex -space-x-px text-sm h-8">
                <li>
                    <a href="{{$users->previousPageUrl()}}" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                </li>
                @foreach($users->getUrlRange(1,ceil($users->total()/$users->perPage())) as $index => $page)
                    <li>
                        <a href="{{$page}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{$index}}</a>
                    </li>
                @endforeach
                <li>
                    <a href="{{$users->nextPageUrl()}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('confirm', e => {
            Swal.fire({
                title: 'Attention!',
                text: 'Do you want to delete item?',
                icon: 'warning',
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                @this[e.callback](...e.argv)
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                }
            })
        })
    </script>
@endpush
