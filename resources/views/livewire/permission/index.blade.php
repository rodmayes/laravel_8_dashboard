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
                        <button class="btn btn-danger ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                            {{ __('Delete Selected') }}
                        </button>
                    </div>
                </div>
                <div class="card-tools col-6">
                    <div class="form-group form-inline float-right">
                        <label for="search" class="col-2 col-form-label">Search:</label>
                        <div class="col-6">
                            <input type="text" wire:model.debounce.300ms="search" class="form-control" />
                        </div>
                        @can('role_create')
                            <a class="btn btn-primary" href="{{ route('admin.permissions.create') }}">
                                {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
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
                                {{ trans('cruds.permission.fields.id') }}
                                @include('components.table.sort', ['field' => 'id'])
                            </th>
                            <th>
                                {{ trans('cruds.permission.fields.title') }}
                                @include('components.table.sort', ['field' => 'title'])
                            </th>
                            <th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr>
                                <td>
                                    <input type="checkbox" value="{{ $permission->id }}" wire:model="selected">
                                </td>
                                <td>
                                    {{ $permission->id }}
                                </td>
                                <td>
                                    {{ $permission->title }}
                                </td>
                                <td>
                                    <div class="flex justify-end">
                                        @can('permission_show')
                                            <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.permissions.show', $permission) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        @endcan
                                        @can('permission_edit')
                                            <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.permissions.edit', $permission) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endcan
                                        @can('permission_delete')
                                            <button class="btn btn-sm btn-danger mr-2" type="button" wire:click="confirm('delete', {{ $permission->id }})" wire:loading.attr="disabled">
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
                        {{ $permissions->links() }}
                    </div>
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
