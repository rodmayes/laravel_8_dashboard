@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-body">
        <div class="pt-3">
            <table class="table table-view">
                <tbody class="bg-white">
                    <tr>
                        <th>
                            {{ trans('playtomic.bookings.fields.id') }}
                        </th>
                        <td>{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('playtomic.bookings.fields.name') }}
                        </th>
                        <td>{{ $booking->name }}</td>
                    </tr>
                   <tr>
                        <th>
                            {{ trans('playtomic.bookings.fields.resource') }}
                        </th>
                        <td>
                            @foreach(explode(",",$booking->resources) as $item)
                                @php $resource = \App\Models\Resource::find($item); @endphp
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $resource->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('playtomic.bookings.fields.timetable') }}
                        </th>
                        <td>
                            @foreach(explode(",",$booking->timetables) as $item)
                                @php $timestable = \App\Models\Timetable::find($item); @endphp
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">{{ $timestable->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('playtomic.bookings.fields.club') }}
                        </th>
                        <td>
                            <span class="bg-gray-300 text-white text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">{{ $booking->club->name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Log
                        </th>
                        <td>
                            <span class="badge badge-relationship">
                                @if($booking->log)
                                    @foreach(json_decode($booking->log, true) as $item)
                                        <p class="text-left">{{ $item }}</p>
                                    @endforeach
                                @endif
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
            <a href="{{ route('playtomic.bookings.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.back') }}
            </a>
            <a href="{{ route('playtomic.bookings.edit', $booking) }}" class="text-white bg-green-700 hover:bg-green-200 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.edit') }}
            </a>
        </div>
    </div>
</div>
@endsection
