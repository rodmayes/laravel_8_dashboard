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
                    @can('user_create')
                        <a class="btn btn-primary float-right" href="{{ route('playtomic.resources.create') }}">
                            {{ trans('global.add') }} {{ trans('playtomic.resources.title_singular') }}
                        </a>
                    @endcan
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
    if (!confirm("{{ trans('global.areYouSure') }}")) {
        return
    }
@this[e.callback](...e.argv)
})
    </script>
@endpush