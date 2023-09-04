@extends('layouts.app')
@section('content')

    <div class="login-box">
        <div class="card card-outline card-warning">
            <div class="card-header bg-warning text-center">
                {{ trans('global.login') }}
            </div>
            <div class="card-body">
                @if(session('message'))
                <div class="shadow bg-green-100 my-4 rounded p-2" role="alert">
                    {{ session('message') }}
                </div>
               @endif
                <p class="login-box-msg">Sign in to start your session</p>
                <form method="POST" action="{{ route('login') }}">
                @csrf
                    <div class="input-group mb-3">
                        <input id="email"
                               name="email"
                               type="text"
                               class="form-control {{ $errors->has('email') ? ' border border-red-500' : '' }}"
                               required
                               autocomplete="email"
                               autofocus
                               placeholder="{{ trans('global.login_email') }}"
                               value="{{ old('email', null) }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @if($errors->has('email'))
                            <div class="text-red-500">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="input-group mb-3">
                        <input id="password"
                               name="password"
                               type="password"
                               class="form-fontrol {{ $errors->has('password') ? ' border border-red-500' : '' }}"
                               required
                               placeholder="{{ trans('global.login_password') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @if($errors->has('password'))
                            <div class="text-red-500">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input
                                    name="remember"
                                    type="checkbox"
                                    id="remember"
                                    class="form-checkbox"/>
                                <label for="remember">
                                    {{ trans('global.remember_me') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">{{ trans('global.login') }}</button>
                        </div>
                    </div>
                </form>
                    @if(Route::has('password.request'))
                    <p class="mb-1">
                        <a href="{{ route('password.request') }}">I forgot my password</a>
                    </p>
                    @endif
                    @if(Route::has('register'))
                    <p class="mb-0">
                        <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
                    </p>
                    @endif
            </div>

        </div>
    </div>
@endsection
