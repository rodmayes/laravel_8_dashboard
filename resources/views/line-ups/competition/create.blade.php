@extends('layouts.admin')
@section('content')
    <h1 class="h5 mb-2">{{ trans('line-ups.competitions.title') }} {{ trans('global.create') }}</h1>
    @livewire('line-ups.competition.create')
@endsection
