@extends('errors::illustrated-layout')

@section('title', __('errors.pageExpired'))
@section('code', '419')
@section('message', __('errors.pageExpired'))
@section('image')
    <div style="background-image: url('/images/hermoso-camino-madera-que-impresionantes-arboles-coloridos-bosque.jpeg');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection
@section('message_custom')
    {{ __('errors.expiredRequest') }}<br>
    {{ __('errors.tryAgain') }}<br>
    {{ __('errors.logout') }}<a href="{{ route('logout') }}">{{ __('errors.here') }}</a>{{ __('errors.logout2') }}
@stop
