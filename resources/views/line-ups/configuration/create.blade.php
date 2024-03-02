@extends('layouts.admin')
@section('content')
    <h1 class="h5 mb-2">{{ trans('line-ups.configuration.title') }} {{ trans('global.create') }}</h1>
    @livewire('line-ups.configuration.create')
@endsection
