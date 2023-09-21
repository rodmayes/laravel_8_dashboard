<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title col-6 p-0">
                    <div class="form-group form-inline p-0">
                        <div class="col-5 form-inline p-0">
                            <label for="perPage" class="col-6 col-form-label">{{trans('global.datatables.per_page')}}:</label>
                            <div class="col-6">
                                <x-select-list class="form-control" required id="perPage" name="perPage" :options="$paginationOptions" wire:model="perPage"/>
                            </div>
                        </div>
                        <div class="form-group col-6 p-0">
                            <label>{{ trans('playtomic.resources.per_club') }}: </label>
                            <div class="col-8">
                                <x-select-list class="form-control" required id="perClub" name="perClub" :options="$clubs->pluck('name','id')" wire:model="perClub"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-tools col-6">
                    <div class="btn-group float-right">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52" aria-expanded="false">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu" style="">
                            @can('user_create')
                                <a class="dropdown-item" href="{{ route('playtomic.bookings.create') }}">
                                    <i class="fa fa-plus-circle"></i> {{ trans('global.add') }} {{ trans('playtomic.bookings.title_singular') }}
                                </a>
                            @endcan
                            <a class="dropdown-item" href="{{ route('playtomic.bookings.booking') }}">
                                <i class="fa fa-calendar-check"></i> Booking
                            </a>
                                <a class="dropdown-item" href="{{ route('playtomic.bookings.view-calendar') }}">
                                    <i class="fa fa-calendar"></i> Calendar mode
                                </a>
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item bg-gradient-pink" wire:click="truncateResources">
                                Truncate data
                            </button>
                            <button class="dropdown-item bg-danger disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                                <i class="fa fa-trash"></i> {{ __('Delete Selected') }}
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-dark btn-sm float-right mr-2" wire:click="$emit('refreshComponent')" data-toggle="tooltip" data-placement="bottom" title="Refresh data">
                        <i class="fas fa-sync"></i>
                    </button>
                    <div class="form-group form-inline col-6 p-0 float-right">
                        <label for="search" class="col-2 col-form-label">Search:</label>
                        <div class="col-10">
                            <input type="text" wire:model.debounce.300ms="search" class="form-control col-12" style="width:100%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <div wire:loading.delay class="col-12 alert alert-info">
                    {{trans('global.datatables.loading')}}...
                </div>
                <table class="table table-hover text-nowrap table-sm">
                    <thead>
                        <tr>
                            <th class="w-9">
                            </th>
                            <th class="w-28">
                                {{ trans('playtomic.bookings.fields.id') }}
                                @include('components.table.sort', ['field' => 'id'])
                            </th>
                            <th>
                                {{ trans('playtomic.bookings.fields.started_at') }}
                                @include('components.table.sort', ['field' => 'started_at'])
                            </th>
                            <th>
                                {{ trans('playtomic.bookings.fields.timetable') }}
                            </th>
                            <th>
                                {{ trans('playtomic.bookings.fields.resource') }}
                            </th>
                            <th>
                                {{ trans('playtomic.bookings.fields.club') }}
                                @include('components.table.sort', ['field' => 'club_id'])
                            </th>
                            <th>Links create</th>
                            <th>
                                Preference
                                @include('components.table.sort', ['field' => 'booking_preference'])
                            </th>
                            <th>
                                {{ trans('playtomic.bookings.fields.status') }}
                                @include('components.table.sort', ['field' => 'status'])
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td><input type="checkbox" value="{{ $booking->id }}" wire:model="selected"></td>
                                <td>{{ $booking->id }}</td>
                                <td>
                                    {{ $booking->started_at->format('d-m-Y') }} ({{ ucfirst($booking->started_at->locale('es')->dayName) }})
                                </td>
                                <td>
                                    @foreach(explode(",",$booking->timetables) as $id)
                                        <span class="badge badge-warning">{{ \App\Models\Timetable::find($id)->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach(explode(",",$booking->resources) as $id)
                                        <span class="badge badge-info">{{ \App\Models\Resource::find($id)->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $booking->club->name }}</td>
                                <td>
                                    @if($booking->started_at->addDays(-((int)$booking->club->days_min_booking))->format('d-m-Y') === \Carbon\Carbon::now()->format('d-m-Y'))
                                        <span class="text-danger">
                                            {{ $booking->started_at->addDays(-((int)$booking->club->days_min_booking))->format('d-m-Y')}}
                                        </span>
                                    @else
                                        {{ $booking->started_at->addDays(-((int)$booking->club->days_min_booking))->format('d-m-Y')}}
                                    @endif
                                </td>
                                <td>
                                    @if($booking->booking_preference === 'timetable')
                                        <span class="badge badge-info" title="Preference {{$booking->booking_preference}}"><i class="fas fa-clock"></i></span>
                                    @else
                                        <span class="badge badge-success" title="Preference {{$booking->booking_preference}}"><i class="fas fa-table-tennis"></i></span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->status === 'on-time')
                                        <span class="badge bg-green">On time</span>
                                    @elseif($booking->status === 'time-out')
                                        <span class="badge bg-gray-dark">Time out</span>
                                    @else
                                        <span class="badge bg-gray-light">Closed</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="btn-group btn-group-sm">
                                        @can('user_show')
                                            <a class="btn btn-xs btn-info " href="{{ route('playtomic.bookings.show', $booking) }}" title="{{ trans('global.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('user_edit')
                                            <a class="btn btn-xs btn-success" href="{{ route('playtomic.bookings.edit', $booking) }}" title="{{ trans('global.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        <a class="btn btn-xs btn-warning" href="{{ route('playtomic.bookings.generate-links', $booking->id) }}" title="{{ trans('playtomic.generate-links.title')  }}">
                                            <i class="fas fa-link"></i>
                                        </a>
                                        @if($booking->status != 'closed')
                                            <button class="btn btn-xs btn-dark" type="button" wire:click="setClosed({{ $booking->id }})" wire:loading.attr="disabled" title="Set closed">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-xs btn-dark" type="button" wire:click="setOntime({{ $booking->id }})" wire:loading.attr="disabled" title="Set On time">
                                                <i class="fas fa-calendar"></i>
                                            </button>
                                        @endif
                                        @can('user_delete')
                                            <button class="btn btn-xs btn-danger" type="button" wire:click="confirm('delete', {{ $booking->id }})" wire:loading.attr="disabled" title="{{ trans('global.delete') }}">
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
            </div>
            <div class="card-footer">
                <div class="pt-3">
                    @if($this->selectedCount)
                        <p class="text-sm leading-5">
                            <span class="font-medium">
                                {{ $this->selectedCount }}
                            </span>
                            {{ __('Entries selected') }}
                        </p>
                    @endif
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
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
