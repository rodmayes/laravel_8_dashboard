<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title col-6">
                    <div class="form-group form-inline">
                        <label for="perPage" class="col-2 col-form-label">{{trans('global.datatables.per_page')}}:</label>
                        <div class="col-4">
                            <select wire:model="perPage" class="form-control select2 col-4">
                                @foreach($paginationOptions as $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-3 form-inline">
                            <label>{{ trans('playtomic.resources.per_club') }}:</label>
                            <select wire:model="perClub" class="form-select form-control col-8">
                                <option value="-1">All</option>
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-danger ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                            {{ __('Delete Selected') }}
                        </button>
                    </div>
                </div>
                <div class="card-tools col-6">
                    <button class="btn btn-info mr-2  float-right" wire:click="truncateResources">
                        Truncate data
                    </button>
                    @can('user_create')
                        <a class="btn btn-primary float-right mr-1" href="{{ route('playtomic.bookings.create') }}">
                            {{ trans('global.add') }} {{ trans('playtomic.bookings.title_singular') }}
                        </a>
                    @endcan
                    <a class="btn btn-success mr-1  float-right" href="{{ route('playtomic.prebooking') }}">
                        {{ trans('playtomic.bookings.prebooking.title_singular') }}
                    </a>
                    <div class="form-group form-inline float-right">
                        <label for="search" class="col-2 col-form-label">Search:</label>
                        <div class="col-7">
                            <input type="text" wire:model.debounce.300ms="search" class="form-control" />
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
