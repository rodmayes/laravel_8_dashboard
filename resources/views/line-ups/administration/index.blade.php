@extends('layouts.admin')
@section('content')
    <h1 class="h5 mb-2">{{ trans('line-ups.administration.title') }}</h1>
    @livewire('line-ups.administration.index')
@endsection
