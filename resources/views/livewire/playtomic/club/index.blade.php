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
                        <div class="form-group form-inline col-6">
                            <label for="search" class="col-2 col-form-label">Search:</label>
                            <div class="col-10">
                                <input type="text" wire:model.debounce.300ms="search" class="form-control col-12" style="width:100%">
                            </div>
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
                                <a class="dropdown-item" href="{{ route('playtomic.clubs.create') }}">
                                    <i class="fa fa-plus-circle"></i> {{ trans('global.add') }} {{ trans('playtomic.clubs.title_singular') }}
                                </a>
                            @endcan
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item bg-danger disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                                <i class="fa fa-trash"></i> {{ __('Delete Selected') }}
                            </button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-dark btn-sm float-right mr-2" wire:click="$emit('refreshComponent')" data-toggle="tooltip" data-placement="bottom" title="Refresh data">
                        <i class="fas fa-sync"></i>
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <div wire:loading.delay class="col-12 alert alert-info">
                    {{trans('global.datatables.loading')}}...
                </div>
                <table class="table table-hover table-sm table-index table-condensed">
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
                                #resources
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
                                <td>{{ $club->resources->count() }}</td>
                                <td class="text-right">
                                    <div class="btn-group btn-group-sm">
                                        @can('user_show')
                                            <a class="btn btn-sm btn-info" href="{{ route('playtomic.clubs.show', $club) }}" title="{{ trans('global.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('user_edit')
                                            <a class="btn btn-sm btn-success " href="{{ route('playtomic.clubs.edit', $club) }}" title="{{ trans('global.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                            <button class="btn btn-sm btn-dark" type="button" wire:click="syncResources({{ $club->id }})" wire:loading.attr="disabled" title="Sync resources">
                                                <i class="fas fa-sync"></i>
                                            </button>
                                        @can('user_delete')
                                            <button class="btn btn-sm btn-danger" type="button" wire:click="confirm('delete', {{ $club->id }})" wire:loading.attr="disabled" title="{{ trans('global.delete') }}">
                                                <i class="fas fa-trash"></i>
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
