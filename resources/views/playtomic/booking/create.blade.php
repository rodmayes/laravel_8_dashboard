@extends('layouts.admin')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        {{ trans('global.create') }}
                        {{ trans('playtomic.bookings.title_singular') }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('playtomic.bookings.index') }}">{{ trans('playtomic.bookings.title') }}</a></li>
                        <li class="breadcrumb-item active"> {{ trans('playtomic.bookings.title_singular') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @livewire('playtomic.booking.create')
        </div>
    </section>
@endsection
