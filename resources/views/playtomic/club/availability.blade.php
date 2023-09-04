@extends('layouts.admin')
@section('content')
<div class="card bg-white">
    <div class="card-header border-b border-blueGray-200">
        <div class="card-header-container">
            <h6 class="card-title">
                Avaibility for: {{$club->name}}
            </h6>
            @can('user_create')
                <a href="{{ route('playtomic.clubs.availability', $club) }}" class="btn btn-secondary">
                    {{ trans('global.back') }}
                </a>
            @endcan
        </div>
    </div>
    @livewire('playtomic.club.availability', [$club])

</div>
@endsection
