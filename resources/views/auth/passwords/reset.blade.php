@extends('layouts.app')
@section('content')

    <div class="login-box">
        <div class="card card-outline card-warning">
            <div class="card-header bg-warning text-center">
                {{ trans('global.reset_password') }}
            </div>

            @if(session('status'))
                <div class="shadow bg-green-100 my-4 rounded p-2" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.request') }}">
            @csrf
                <input name="token" value="{{ $token }}" type="hidden">

                <div class="relative w-full mb-3">
                    <label class="block uppercase text-gray-700 text-xs font-bold mb-2"
                           for="email">
                        {{ trans('global.login_email') }}
                    </label>
                    <input id="email"
                           name="email"
                           type="text"
                           class="px-3 py-3 placeholder-gray-400 text-gray-700 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150{{ $errors->has('email') ? ' border border-red-500' : '' }}"
                           required
                           autocomplete="email"
                           autofocus
                           placeholder="{{ trans('global.login_email') }}"
                           value="{{ old('email', $email) }}">

                    @if($errors->has('email'))
                        <div class="text-red-500">
                            {{ $errors->first('email') }}
                        </div>
                  @endif
                </div>
                <div class="relative w-full mb-3">
                    <label class="block uppercase text-gray-700 text-xs font-bold mb-2"
                           for="grid-password">
                        {{ trans('global.login_password') }}
                    </label>
                    <input id="password"
                           name="password"
                           type="password"
                           class="px-3 py-3 placeholder-gray-400 text-gray-700 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150{{ $errors->has('password') ? ' border border-red-500' : '' }}"
                           required
                           placeholder="{{ trans('global.login_password') }}">

                    @if($errors->has('password'))
                        <div class="text-red-500">
                            {{ $errors->first('password') }}
                        </div>
                     @endif
                </div>
                <div class="relative w-full mb-3">
                    <label class="block uppercase text-gray-700 text-xs font-bold mb-2"
                           for="grid-password">
                        {{ trans('global.login_password_confirmation') }}
                    </label>
                    <input id="password_confirmation"
                           name="password_confirmation"
                           type="password"
                           class="px-3 py-3 placeholder-gray-400 text-gray-700 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150{{ $errors->has('password') ? ' border border-red-500' : '' }}"
                           required
                           placeholder="{{ trans('global.login_password') }}">

                   @if($errors->has('password_confirmation'))
                        <div class="text-red-500">
                            {{ $errors->first('password_confirmation') }}
                        </div>
                     @endif
                </div>

                <div class="text-center mt-6">
                    <button
                            class="bg-gray-900 text-white active:bg-gray-700 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150"
                            type="submit">
                        {{ trans('global.reset_password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
