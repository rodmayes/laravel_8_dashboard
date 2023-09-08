<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title col-8 form-inline">
                    <div class="form-group col-3">
                        <label for="perPage" class="col-form-label">{{trans('global.datatables.per_page')}}:</label>
                        <div class="col-8">
                            <select wire:model="perPage" class="form-control select2">
                                @foreach($paginationOptions as $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-3">
                        <label>{{ trans('playtomic.resources.per_club') }}: </label>
                        <select wire:model="perClub" class="select2 form-control">
                            <option value="-1">All</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group form-inline col-6">
                        <label for="search" class="col-2 col-form-label">Search:</label>
                        <div class="col-7">
                            <input type="text" wire:model.debounce.300ms="search" class="form-control col-12" style="width:100%">
                        </div>
                    </div>
                </div>
                <div class="card-tools col-4">
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
                                <i class="fa fa-calendar-check"></i> {{ trans('playtomic.bookings.prebooking.title_singular') }}
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
                </div>
            </div>
            <div class="card-body">
                <div wire:loading.delay class="col-12 alert alert-info">
                    {{trans('global.datatables.loading')}}...
                </div>
                <table class="table table-hover text-nowrap">
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
                                @include('components.table.sort', ['field' => 'timetable_id'])
                            </th>
                            <th>
                                {{ trans('playtomic.bookings.fields.resource') }}
                            </th>
                            <th>Links create</th>
                            <th>
                                {{ trans('playtomic.bookings.fields.club') }}
                                @include('components.table.sort', ['field' => 'club_id'])
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
                                <td>{{ $booking->started_at->format('d-m-Y') }}</td>
                                <td>{{ $booking->timetable->name }}</td>
                                <td>
                                    @foreach(explode(",",$booking->resources) as $id)
                                        <span class="badge badge-info">{{ \App\Models\Resource::find($id)->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $booking->started_at->addDays(-((int)$booking->club->days_min_booking))->format('d-m-Y')}}</td>
                                <td>{{ $booking->club->name }}</td>
                                <td>
                                    @if($booking->status === 'on-time')
                                        <span class="badge bg-green">On time</span>
                                    @elseif($booking->status === 'time-out')
                                        <span class="badge bg-gray-dark">Time out</span>
                                    @else
                                        <span class="badge bg-gray-light">Closed</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex justify-end">
                                        @can('user_show')
                                            <a class="btn btn-xs btn-info " href="{{ route('playtomic.bookings.show', $booking) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        @endcan
                                        @can('user_edit')
                                            <a class="btn btn-xs btn-success" href="{{ route('playtomic.bookings.edit', $booking) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endcan
                                        <a class="btn btn-xs btn-warning" href="{{ route('playtomic.bookings.generate-links', $booking->id) }}">
                                            {{ trans('playtomic.generate-links.title') }}
                                        </a>
                                        @if($booking->status != 'closed')
                                            <button class="btn btn-xs btn-dark" type="button" wire:click="setClosed({{ $booking->id }})" wire:loading.attr="disabled">
                                                Set closed
                                            </button>
                                        @else
                                            <button class="btn btn-xs btn-dark" type="button" wire:click="setOntime({{ $booking->id }})" wire:loading.attr="disabled">
                                                Set on time
                                            </button>
                                        @endif
                                        @can('user_delete')
                                            <button class="btn btn-xs btn-danger" type="button" wire:click="confirm('delete', {{ $booking->id }})" wire:loading.attr="disabled">
                                                {{ trans('global.delete') }}
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
        $('#playtomic-tabs').IFrame('createTab', 'Home', 'https://playtomic.io/fibra-premium-sports-club/23e59812-5361-4e0e-8223-f0b0503a59db?q=PADEL~2023-09-01~~~', 'index', true)
        Livewire.on('confirm', e => {
    if (!confirm("{{ trans('global.areYouSure') }}")) {
        return
    }
@this[e.callback](...e.argv)
})
    </script>
@endpush
