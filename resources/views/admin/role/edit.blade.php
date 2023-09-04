@extends('layouts.admin')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        {{ trans('global.edit') }}
                        {{ trans('cruds.role.title_singular') }}:
                        {{ trans('cruds.role.fields.id') }}
                        {{ $role->id }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.roles.index') }}">{{ trans('cruds.role.title') }}</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @livewire('role.edit', [$role])
        </div>
    </section>
@endsection
