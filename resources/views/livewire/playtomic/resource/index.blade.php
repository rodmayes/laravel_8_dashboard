<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title col-8 form-inline">
                    <div class="form-group col-3">
                        <label for="perPage" class="col-form-label">{{trans('global.datatables.per_page')}}:</label>
                        <div class="col-8">
                            <x-select-list class="form-control" required id="perPage" name="perPage" :options="$paginationOptions" wire:model="perPage"/>
                        </div>
                    </div>
                    <div class="form-group col-3">
                        <label>{{ trans('playtomic.resources.per_club') }}: </label>
                        <div class="col-8">
                            <x-select-list class="form-control" required id="perClub" name="perClub" :options="$clubs->pluck('name','id')" wire:model="perClub"/>
                        </div>

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
                                <a class="dropdown-item" href="{{ route('playtomic.resources.create') }}">
                                    <i class="fa fa-plus-circle"></i> {{ trans('global.add') }} {{ trans('playtomic.resources.title_singular') }}
                                </a>
                            @endcan
                            <div class="dropdown-divider"></div>
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
                                {{ trans('playtomic.resources.fields.id') }}
                                @include('components.table.sort', ['field' => 'id'])
                            </th>
                            <th>
                                {{ trans('playtomic.resources.fields.name') }}
                                @include('components.table.sort', ['field' => 'name'])
                            </th>
                            <th>
                                {{ trans('playtomic.resources.fields.playtomic_id') }}
                                @include('components.table.sort', ['field' => 'playtomic_id'])
                            </th>
                            <th>
                                {{ trans('playtomic.resources.fields.club') }}
                                @include('components.table.sort', ['field' => 'club'])
                            </th>
                            <th>
                                {{ trans('playtomic.resources.fields.priority') }}
                                @include('components.table.sort', ['field' => 'priority'])
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resources as $resource)
                            <tr>
                                <td>
                                    <input type="checkbox" value="{{ $resource->id }}" wire:model="selected">
                                </td>
                                <td>{{ $resource->id }}</td>
                                <td>{{ $resource->name }}</td>
                                <td>{{ $resource->playtomic_id }}</td>
                                <td>{{ $resource->club->name }}</td>
                                <td>{{ $resource->priority }}</td>
                                <td>
                                    <div class="flex justify-end">
                                        @can('user_show')
                                            <a class="btn btn-sm btn-info mr-2" href="{{ route('playtomic.resources.show', $resource) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        @endcan
                                        @can('user_edit')
                                            <a class="btn btn-sm btn-success mr-2" href="{{ route('playtomic.resources.edit', $resource) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endcan
                                        @can('user_delete')
                                            <button class="btn btn-sm btn-danger mr-2" type="button" wire:click="confirm('delete', {{ $resource->id }})" wire:loading.attr="disabled">
                                                {{ trans('global.delete') }}
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">{{trans('global.datatables.no_items_found')}}.</td>
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
                    {{ $resources->links() }}
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
