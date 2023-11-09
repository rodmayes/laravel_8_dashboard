@extends('layouts.app')
@section('content')

     <!-- Container -->
     <div class="flex flex-wrap min-h-screen w-full content-center justify-center bg-gray-100 py-10" style="background-image: url('{{asset('images/fresh-green-background.jpeg')}}')">

         <!-- Login component -->
         <div class="flex shadow-md">
             <!-- Login form -->
             <div class="flex flex-wrap content-center justify-center rounded-l-md bg-white" style="width: 24rem; height: 32rem;">
                 @if(session('message'))
                     <div class="shadow bg-green-100 my-4 rounded p-2" role="alert">
                         {{ session('message') }}
                     </div>
                 @endif
                 <div class="w-72">
                     <!-- Heading -->
                     <h1 class="text-xl font-semibold">Welcome back</h1>
                     <small class="text-gray-400">Welcome back! Please enter your details</small>

                     <!-- Form -->
                     <form class="mt-4" method="POST" action="{{ route('login') }}">
                         @csrf
                         <div class="mb-3">
                             <label class="mb-2 block text-xs font-semibold">{{ trans('global.login_email') }}</label>
                             <input id="email"
                                    name="email"
                                    type="text"
                                    class="block w-full rounded-md border border-gray-300 focus:border-teal-700 focus:outline-none focus:ring-1 focus:ring-teal-700 py-1 px-1.5 text-gray-500"
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

                         <div class="mb-3">
                             <label class="mb-2 block text-xs font-semibold">{{ trans('global.login_password') }}</label>
                             <input id="password"
                                    name="password"
                                    type="password"
                                    class="block w-full rounded-md border border-gray-300 focus:border-teal-700 focus:outline-none focus:ring-1 focus:ring-teal-700 py-1 px-1.5 text-gray-500"
                                    required
                                    placeholder="{{ trans('global.login_password') }}">
                             @if($errors->has('password'))
                                 <div class="text-red-500">
                                     {{ $errors->first('password') }}
                                 </div>
                             @endif
                         </div>

                         <div class="mb-3 flex flex-wrap content-center">
                                 <input
                                     name="remember"
                                     type="checkbox"
                                     id="remember"
                                     class="mr-1 checked:bg-teal-700"/>
                                 <label for="remember" class="mr-auto text-xs font-semibold">
                                     {{ trans('global.remember_me') }}
                                 </label>
                             <a href="{{ route('password.request') }}" class="text-xs font-semibold text-teal-700">{{ trans('global.forgot_password') }}</a>
                         </div>

                         <div class="mb-3">
                             <button class="mb-1.5 block w-full text-center text-white bg-green-600 hover:bg-green-400 px-2 py-1.5 rounded-md">{{ trans('global.login') }}</button>
                         </div>

                         <!-- Footer -->
                         <div class="text-center">
                             <span class="mr-2 w-full captcha">
                                 <img src="{!! captcha_src() !!}" style="width:100%" class="captcha"/>
                             </span>
                             <label for="captcha" class="sr-only">Captcha</label>
                             <div class="flex rounded-lg shadow-sm">
                                 <input type="text" id="captcha" name="captcha"
                                        class="block w-full rounded-md border border-gray-300 focus:border-teal-700 focus:outline-none focus:ring-1 focus:ring-teal-700 py-1 px-1.5 text-gray-500"
                                 >
                                 <button type="button" title="Refresh captcha"
                                         class="btn-refresh flex-shrink-0 inline-flex justify-center items-center gap-x-2 text-center text-white bg-green-600 hover:bg-green-400 px-2 py-1.5 rounded-md">
                                     <i class="flex-shrink-0 h-4 w-4  fas fa-sync"></i>
                                 </button>
                             </div>
                             @if ($errors->has('captcha'))
                                 <span class="text-2xs text-red-500"><strong>{{ $errors->first('captcha') }}</strong></span>
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

             <!-- Login banner -->
             <div class="flex flex-wrap content-center justify-center rounded-r-md" style="width: 24rem; height: 32rem;">
                 <img class="w-full h-full bg-center bg-no-repeat bg-cover rounded-r-md" src="{{asset('images/green-side-login.webp')}}">
             </div>

         </div>

         <!-- Credit -->
         <!--
         <div class="mt-3 w-full">
             <p class="text-center">Made by <a target="_blank" href="https://www.instagram.com/_inubayuaji/" class="text-purple-700">Inu Bayu Aji</a> and ispired by <a target="_blank" href="https://dribbble.com/shots/17564792-Log-in-page-Untitled-UI" class="text-purple-700">this</a>.</p>
         </div>
         -->
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
