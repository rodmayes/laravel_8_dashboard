@extends('layouts.admin')
@section('content')
    <h1 class="h5 mb-2">Dashboard</h1>
    <div class="bg-gray-100 flex-1 p-6 md:mt-16">
        @if(session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="grid grid-cols-3 lg:grid-cols-1 gap-5">
            <div class="card col-span-1">

                <div class="card-body h-full flex flex-col justify-between">

                    <div>
                        <h1 class="text-lg font-bold tracking-wide">Congratulations {{ auth()->user()->name }}!</h1>
                        <p class="text-gray-600 mt-2">You are logged in!!</p>
                    </div>

                    <div class="flex flex-row mt-10 items-end">

                        <div class="flex-1">
                            <h1 class="font-extrabold text-4xl text-teal-400">$89k</h1>
                            <p class="mt-3 mb-4 text-xs text-gray-500">You have done 57.6% more sales today.</p>
                            <a href="#" class="btn-shadow py-3">
                                view sales
                            </a>
                        </div>

                        <div class="flex-1 ml-10 w-32 h-32 lg:w-auto lg:h-auto overflow-hidden">
                            <img class="object-cover" src="images//congrats.svg">
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection
