@extends('errors::illustrated-layout')

@section('title', __('errors.serverUnavailable'))
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'errors.serverUnavailable'))
@section('image')
    <div style="background-image: url('/images/hermoso-camino-madera-que-impresionantes-arboles-coloridos-bosque.jpeg');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection
@section('message_custom')
    {{ __('errors.error') }}<br>
    {{ __('errors.error2') }}<br>
    {{ __('errors.error5') }}<br>
    {{ __('errors.error4') }}
@stop
