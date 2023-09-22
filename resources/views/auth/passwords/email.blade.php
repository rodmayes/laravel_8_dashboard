@extends('layouts.app')
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="/"><b>Rodmayes</b> ERM</a>
        </div>
        <div class="card card-outline card-warning">
            <div class="card-header bg-warning text-center">
                {{ trans('global.reset_password') }}
            </div>
            <div class="card-body login-card-body"
                @if(session('status'))
                    <div class="shadow bg-green-100 my-4 rounded p-2" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                @csrf

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
                               value="{{ old('email', null) }}">

                            @if($errors->has('email'))
                            <div class="text-red-500">
                                {{ $errors->first('email') }}
                            </div>
                            @endif
                    </div>

                    <div class="text-center mt-6">
                        <button
                                class="bg-gray-900 text-white active:bg-gray-700 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150"
                                type="submit">
                            {{ trans('global.send_password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
