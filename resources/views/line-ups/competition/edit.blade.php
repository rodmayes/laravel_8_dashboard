@extends('layouts.admin')
@section('content')
    <h1 class="h5 mb-2">{{ trans('line-ups.competitions.title') }} {{ trans('global.edit') }}</h1>
    @livewire('line-ups.competition.edit', [$competition])
@endsection
