@extends('layouts.admin')
@section('content')
    <h1 class="h5 mb-2">{{ trans('line-ups.configuration.title') }} {{ trans('global.list') }}</h1>
    @livewire('line-ups.configuration.index')
@endsection
