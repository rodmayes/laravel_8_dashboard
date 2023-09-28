@extends('layouts.admin')
@section('content')

<div class="card bg-blueGray-100">
    <div class="card-header">
        <div class="card-header-container">
            <h6 class="card-title">
                {{ trans('global.view') }}
                {{ trans('cruds.user.title_singular') }}:
                {{ trans('cruds.user.fields.id') }}
                {{ $user->id }}
            </h6>
        </div>
    </div>

    <div class="card-body">
        <div class="pt-3">
            <table class="table table-view">
                <tbody class="bg-white">
                    <tr>
                        <th>{{ trans('cruds.user.fields.id') }}</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.name') }}</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.email') }}</th>
                        <td>
                            <a class="link-light-blue" href="mailto:{{ $user->email }}">
                                <i class="far fa-envelope fa-fw">
                                </i>
                                {{ $user->email }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.email_verified_at') }}</th>
                        <td>{{ $user->email_verified_at }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.playtomic_id') }}</th>
                        <td>{{ $user->playtomic_id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.playtomic_token') }}</th>
                        <td>{{ $user->playtomic_token }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.roles') }}</th>
                        <td>
                            @foreach($user->roles as $key => $entry)
                                <span class="badge badge-relationship">{{ $entry->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="w-full items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
            <a href="{{ route('admin.users.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.back') }}
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="float-right text-white bg-green-700 hover:bg-green-200 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.edit') }}
            </a>
        </div>
    </div>
</div>
@endsection
