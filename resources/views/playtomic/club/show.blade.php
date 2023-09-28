@extends('layouts.admin')
@section('content')

<div class="card bg-blueGray-100">
    <div class="card-header">
        <div class="card-header-container">
            <h6 class="card-title">
                {{ trans('global.view') }}
                {{ trans('playtomic.clubs.title_singular') }}:
                {{ trans('playtomic.clubs.fields.id') }}
                {{ $club->id }}
            </h6>
        </div>
    </div>

    <div class="card-body">
        <div class="pt-3 mb-2">
            <table class="table table-view">
                <tbody class="bg-white">
                    <tr>
                        <th>
                            {{ trans('playtomic.clubs.fields.id') }}
                        </th>
                        <td>{{ $club->id }}</td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('playtomic.clubs.fields.name') }}
                        </th>
                        <td>{{ $club->name }}</td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('playtomic.clubs.fields.playtomic_id') }}
                        </th>
                        <td>{{ $club->playtomic_id }}</td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('playtomic.clubs.fields.days_min_booking') }}
                        </th>
                        <td>{{ $club->days_min_booking }}</td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('playtomic.clubs.fields.resources') }}
                        </th>
                        <td>
                            @foreach($club->resources as $key => $entry)
                                <span class="bg-gray-300 text-black text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">{{ $entry->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="w-full items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
            <a href="{{ route('playtomic.clubs.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.back') }}
            </a>
            <a href="{{ route('playtomic.clubs.edit', $club) }}" class="float-right text-white bg-green-700 hover:bg-green-200 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.edit') }}
            </a>
        </div>
    </div>
</div>
@endsection
