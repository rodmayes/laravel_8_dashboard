@extends('layouts.admin')
@section('content')
    <h1 class="h5 mb-2">{{ trans('line-ups.configuration.title') }} {{ trans('global.edit') }}</h1>
    @livewire('line-ups.configuration.edit', [$configuration])
@endsection
