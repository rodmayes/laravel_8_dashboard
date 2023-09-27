    <div class="card">
        <div wire:loading.delay class="col-12 alert alert-info">
            {{trans('global.datatables.loading')}}...
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex items-center justify-between pb-4 bg-white dark:bg-gray-900">
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
                            <label class="block font-bold md:text-right mb-1 md:mb-0 pr-4" for="perClub">
                                {{ trans('playtomic.resources.per_club') }}:
                            </label>
                            <select wire:model="perClub" class="block appearance-none w-full border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }}</option>
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
                                <a class="block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-primary-100 hover:text-gray" href="{{ route('playtomic.bookings.create') }}">
                                    <i class="fa fa-plus-circle"></i> {{ trans('global.add') }} {{ trans('playtomic.bookings.title_singular') }}
                                </a>
                            </li>
                            <li>
                                <a class="block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-primary-100 hover:text-gray" href="{{ route('playtomic.bookings.booking') }}">
                                    <i class="fa fa-calendar-check"></i> Booking
                                </a>
                            </li>
                            <li>
                                <button class="block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-primary-100 hover:text-gray" wire:click="truncateResources">
                                    <i class="fa fa-times"></i> Truncate data
                                </button>
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
                    <th scope="col" class="px-6 py-3">
                        {{ trans('playtomic.bookings.fields.id') }}
                        @include('components.table.sort', ['field' => 'id'])
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ trans('playtomic.bookings.fields.started_at') }}
                        @include('components.table.sort', ['field' => 'started_at'])
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ trans('playtomic.bookings.fields.timetable') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ trans('playtomic.bookings.fields.resource') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ trans('playtomic.bookings.fields.club') }}
                        @include('components.table.sort', ['field' => 'club_id'])
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Links create
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Preference
                        @include('components.table.sort', ['field' => 'booking_preference'])
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ trans('playtomic.bookings.fields.status') }}
                        @include('components.table.sort', ['field' => 'status'])
                    </th>
                    <th scope="col" class="px-6 py-3">
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($bookings as $booking)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-2 py-2">
                        <div class="flex items-center">
                            <input type="checkbox" value="{{ $booking->id }}" wire:model="selected" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                    </td>
                    <td class="px-2 py-2">{{ $booking->id }}</td>
                    <td class="px-2 py-2">
                        {{ $booking->started_at->format('d-m-Y') }} ({{ ucfirst($booking->started_at->locale('es')->dayName) }})
                    </td>
                    <td class="px-2 py-2">
                        @foreach(explode(",",$booking->timetables) as $id)
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">{{ \App\Models\Timetable::find($id)->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-2 py-2">
                        @foreach(explode(",",$booking->resources) as $id)
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ \App\Models\Resource::find($id)->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-2 py-2">
                        {{ $booking->club->name }}
                    </td>
                    <td class="px-2 py-2">
                        @if($booking->started_at->addDays(-((int)$booking->club->days_min_booking))->format('d-m-Y') === \Carbon\Carbon::now()->format('d-m-Y'))
                            <span class="text-red-500">
                                    {{ $booking->started_at->addDays(-((int)$booking->club->days_min_booking))->format('d-m-Y')}}
                                </span>
                        @else
                            {{ $booking->started_at->addDays(-((int)$booking->club->days_min_booking))->format('d-m-Y')}}
                        @endif
                    </td>
                    <td class="px-2 py-2">
                        @if($booking->booking_preference === 'timetable')
                            <span class="text-pink-400" title="Preference {{$booking->booking_preference}}"><i class="fas fa-clock"></i></span>
                        @else
                            <span class="text-blue-500" title="Preference {{$booking->booking_preference}}"><i class="fas fa-table-tennis"></i></span>
                        @endif
                    </td>
                    <td class="px-2 py-2">
                        @if($booking->status === 'on-time')
                            <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">On time</span>
                        @elseif($booking->status === 'time-out')
                            <span class="bg-indigo-100 text-indigo-900 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">Time out</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Closed</span>
                        @endif
                    </td>
                    <td class="px-2 py-2">
                        <div class="inline-flex">
                            @can('user_show')
                                <a class="px-2 py-2 text-xs text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900" href="{{ route('playtomic.bookings.show', $booking) }}" title="{{ trans('global.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endcan
                            @can('user_edit')
                                <a class="px-2 py-2 text-xs text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800" href="{{ route('playtomic.bookings.edit', $booking) }}" title="{{ trans('global.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan
                            <a class="px-2 py-2 text-xs text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" href="{{ route('playtomic.bookings.generate-links', $booking->id) }}" title="{{ trans('playtomic.generate-links.title')  }}">
                                <i class="fas fa-link"></i>
                            </a>
                            @if($booking->status != 'closed')
                                <button class="px-2 py-2 text-xs text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700" wire:click="setClosed({{ $booking->id }})" wire:loading.attr="disabled" title="Set closed">
                                    <i class="fas fa-times"></i>
                                </button>
                            @else
                                <button class="px-2 py-2 text-xs text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"  wire:click="setOntime({{ $booking->id }})" wire:loading.attr="disabled" title="Set On time">
                                    <i class="fas fa-calendar"></i>
                                </button>
                            @endif
                            @can('user_delete')
                                <button class="px-2 py-2 text-xs text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800" wire:click="confirmDelete({{ $booking->id }})" wire:loading.attr="disabled" title="{{ trans('global.delete') }}">
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
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> <span class="font-semibold text-gray-900 dark:text-white">{{$bookings->currentPage()}}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $bookings->total() }}</span></span>
                @if($this->selectedCount)
                    <p class="text-sm leading-5">
                        {{ $this->selectedCount }}
                        {{ __('Entries selected') }}
                    </p>
                @endif
                <ul class="inline-flex -space-x-px text-sm h-8">
                    <li>
                        <a href="{{$bookings->previousPageUrl()}}" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                    </li>
                    @foreach($bookings->getUrlRange(1,ceil($bookings->total()/$bookings->perPage())) as $index => $page)
                        <li>
                            <a href="{{$page}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{$index}}</a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{$bookings->nextPageUrl()}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
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
