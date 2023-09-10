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
                    </div>
                </div>
                <div class="card-tools col-">
                    <button class="btn btn-danger  disabled:opacity-50 disabled:cursor-not-allowed float-right" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                        {{ __('Delete Selected') }}
                    </button>
                    <div class="form-group form-inline float-right">
                        <label for="search" class="col-2 col-form-label">Search:</label>
                        <div class="col-7">
                            <input type="text" wire:model.debounce.300ms="search" class="form-control" />
                        </div>
                        @can('user_create')
                            <a class="btn btn-primary" href="{{ route('playtomic.clubs.create') }}">
                                {{ trans('global.add') }} {{ trans('playtomic.clubs.title_singular') }}
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div wire:loading.delay class="col-12 alert alert-info">
                    {{trans('global.datatables.loading')}}...
                </div>
                <table class="table table-index w-full">
                    <thead>
                        <tr>
                            <th class="w-9">
                            </th>
                            <th class="w-28">
                                {{ trans('playtomic.clubs.fields.id') }}
                                @include('components.table.sort', ['field' => 'id'])
                            </th>
                            <th>
                                {{ trans('playtomic.clubs.fields.name') }}
                                @include('components.table.sort', ['field' => 'name'])
                            </th>
                            <th>
                                {{ trans('playtomic.clubs.fields.playtomic_id') }}
                                @include('components.table.sort', ['field' => 'playtomic_id'])
                            </th>
                            <th>
                                {{ trans('playtomic.clubs.fields.days_min_booking') }}
                                @include('components.table.sort', ['field' => 'days_min_booking'])
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clubs as $club)
                            <tr>
                                <td>
                                    <input type="checkbox" value="{{ $club->id }}" wire:model="selected">
                                </td>
                                <td>{{ $club->id }}</td>
                                <td>{{ $club->name }}</td>
                                <td>{{ $club->playtomic_id }}</td>
                                <td>{{ $club->days_min_booking }}</td>
                                <td>
                                    <div class="flex justify-end">
                                        @can('user_show')
                                            <a class="btn btn-sm btn-info mr-2" href="{{ route('playtomic.clubs.show', $club) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        @endcan
                                        @can('user_edit')
                                            <a class="btn btn-sm btn-success mr-2" href="{{ route('playtomic.clubs.edit', $club) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endcan
                                        @can('user_delete')
                                            <button class="btn btn-sm btn-danger mr-2" type="button" wire:click="confirm('delete', {{ $club->id }})" wire:loading.attr="disabled">
                                                {{ trans('global.delete') }}
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">{{trans('global.datatables.no_items_found')}}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                        {{ $clubs->links() }}
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
