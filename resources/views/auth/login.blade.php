@extends('layouts.app')
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="/"><b>Rodmayes</b> ERM</a>
        </div>
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
                    </div>
                    <div class="mt-2">
                        <span class="mr-2 w-full captcha"><img src="{!! captcha_src() !!}" style="width:100%"/></span>
                        <div class="relative w-full">
                            <input type="text" id="captcha" name="captcha" class="block p-2.5 w-full z-20 text-sm text-gray-900 rounded-r-lg border-l-gray-50 border-l-2 border border-dark dark:bg-gray-700 dark:border-l-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                   placeholder="Enter captcha" required>
                            <button type="button"
                                    class="btn-refresh absolute top-0 right-0 p-2.5 text-sm font-medium h-full text-white bg-green-500 rounded-r-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700"
                            >
                                <i class="fas fa-sync"></i>
                            </button>
                        </div>
                        @if ($errors->has('captcha'))
                            <span class="text-2xs text-red-500"><strong>{{ $errors->first('captcha') }}</strong></span>
                        @endif
                    </div>
                    <div class="w-full">
                            <button type="submit" class="btn btn-primary btn-block mt-2">{{ trans('global.login') }}</button>
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
                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(".btn-refresh").click(function(){
            $.ajax({
                type:'GET',
                url:'/refresh_captcha',
                success:function(data){
                    $(".captcha span").html(data.captcha);
                }
            });
        });
    </script>
@endpush
