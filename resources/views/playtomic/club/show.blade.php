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
        <div class="pt-3">
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
                                <span class="badge badge-info">{{ $entry->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <a href="{{ route('playtomic.clubs.index') }}" class="btn btn-secondary">
                {{ trans('global.back') }}
            </a>
        </div>
    </div>
</div>
@endsection
