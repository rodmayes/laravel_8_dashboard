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
        <div class="form-group">
            <a href="{{ route('playtomic.resources.index') }}" class="btn btn-secondary">
                {{ trans('global.back') }}
            </a>
        </div>
    </div>
</div>
@endsection
