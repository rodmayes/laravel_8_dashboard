@extends('layouts.admin')
@section('content')
    <h1 class="h5 mb-2">{{ trans('line-ups.dashboard.title') }}</h1>
    @livewire('line-ups.dashboard.index')
@endsection
