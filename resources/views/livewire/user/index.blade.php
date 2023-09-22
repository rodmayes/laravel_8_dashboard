<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title col-6 p-0">
                    <div class="form-group form-inline p-0">
                        <div class="col-5 form-inline p-0">
                            <label for="perPage" class="col-6 col-form-label">{{trans('global.datatables.per_page')}}:</label>
                            <div class="col-6">
                                <select wire:model="perPage" class="form-control select2">
                                    @foreach($paginationOptions as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
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
                <div class="card-tools col-6">
                    <div class="form-group form-inline float-right">
                        <div class="btn-group float-right">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52" aria-expanded="false">
                                <i class="fas fa-bars"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" role="menu" style="">
                                @can('user_create')
                                    <a class="dropdown-item" href="{{ route('admin.users.create') }}">
                                        <i class="fa fa-plus-circle"></i> {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
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
            </div>
            <div class="card-body table-responsive">
                <div wire:loading.delay class="col-12 alert alert-info">
                    {{trans('global.datatables.loading')}}...
                </div>
            <table class="table table-index table-hover table-sm">
                <thead>
                    <tr>
                        <th class="w-9">
                        </th>
                        <th class="w-28">
                            {{ trans('cruds.user.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                            @include('components.table.sort', ['field' => 'name'])
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                            @include('components.table.sort', ['field' => 'email'])
                        </th>
                        <th>
                            {{ trans('playtomic.clubs.fields.playtomic_id') }}
                            @include('components.table.sort', ['field' => 'playtomic_id'])
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $user->id }}" wire:model="selected">
                            </td>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>
                                <a class="link-light-blue" href="mailto:{{ $user->email }}">
                                    <i class="far fa-envelope fa-fw">
                                    </i>
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td>{{ $user->playtomic_id }}</td>
                            <td>
                                @foreach($user->roles as $key => $entry)
                                    <span class="badge badge-dark">{{ $entry->title }}</span>
                                @endforeach
                            </td>
                            <td class="text-right">
                                <div class="btn-group btn-group-sm">
                                    @can('user_show')
                                        <a class="btn btn-sm btn-info" href="{{ route('admin.users.show', $user) }}" title="Show">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('user_edit')
                                        <a class="btn btn-sm btn-success" href="{{ route('admin.users.edit', $user) }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @canImpersonate($guard = null)
                                        <button class="btn btn-sm btn-dark" type="button" wire:click="impersonate({{ $user }})" title="Impersonate">
                                            <i class="fas fa-user-circle"></i>
                                        </button>
                                    @endCanImpersonate
                                    @can('user_delete')
                                        <button class="btn btn-sm btn-danger" type="button" wire:click="confirm('delete', {{ $user->id }})" wire:loading.attr="disabled">
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
                    {{ $users->links() }}
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
