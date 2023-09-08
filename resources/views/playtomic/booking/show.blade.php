@extends('layouts.admin')
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        {{ trans('global.view') }}
                        {{ trans('playtomic.bookings.title_singular') }}:
                        {{ trans('playtomic.bookings.fields.id') }}
                        {{ $booking->id }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('playtomic.bookings.index') }}">{{ trans('playtomic.bookings.title') }}</a></li>
                        <li class="breadcrumb-item active"> {{ trans('playtomic.bookings.title_singular') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
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
                                            <span class="badge badge-success">{{ $resource->name }}</span>
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
                                            <span class="badge badge-warning">{{ $timestable->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('playtomic.bookings.fields.club') }}
                                    </th>
                                    <td>
                                        <span class="badge badge-relationship">{{ $booking->club->name }}</span>
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
                    <div class="form-group">
                        <a href="{{ route('playtomic.bookings.index') }}" class="btn btn-secondary">
                            {{ trans('global.back') }}
                        </a>
                        <a href="{{ route('playtomic.bookings.edit', $booking) }}" class="btn btn-primary float-right">
                            {{ trans('global.edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
