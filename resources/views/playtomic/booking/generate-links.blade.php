@extends('layouts.admin')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        {{ trans('playtomic.generate-links.generated-links') }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ trans('global.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ trans('playtomic.generate-links.generated-links') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('playtomic.booking.generate-links', [$booking])
            </div>
        </div>
    </section>
@endsection
