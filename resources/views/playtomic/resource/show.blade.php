@extends('layouts.admin')
@section('content')

<div class="card bg-blueGray-100">
    <div class="card-header">
        <div class="card-header-container">
            <h6 class="card-title">
                {{ trans('global.view') }}
                {{ trans('playtomic.resources.title_singular') }}:
                {{ trans('playtomic.resources.fields.id') }}
                {{ $resource->id }}
            </h6>
        </div>
    </div>

    <div class="card-body">
        <div class="pt-3">
            <table class="table table-view">
                <tbody class="bg-white">
                    <tr>
                        <th>
                            {{ trans('playtomic.resources.fields.id') }}
                        </th>
                        <td>
                            {{ $resource->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('playtomic.resources.fields.name') }}
                        </th>
                        <td>
                            {{ $resource->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('playtomic.resources.fields.playtomic_id') }}
                        </th>
                        <td>
                            {{ $resource->playtomic_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('playtomic.resources.fields.club') }}
                        </th>
                        <td>
                            <span class="badge badge-info">{{ $resource->club->name }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
            <a href="{{ route('playtomic.resources.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.back') }}
            </a>
            <a href="{{ route('playtomic.resources.edit', $resource) }}" class="text-white bg-green-700 hover:bg-green-200 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.edit') }}
            </a>
        </div>
    </div>
</div>
@endsection
