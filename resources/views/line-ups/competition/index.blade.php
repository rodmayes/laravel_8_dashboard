@extends('layouts.admin')
@section('content')
    <h1 class="h5 mb-2">{{ trans('line-ups.competitions.title') }} {{ trans('global.list') }}</h1>
    @livewire('line-ups.competition.index')
@endsection
