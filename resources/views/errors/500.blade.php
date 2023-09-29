@extends('errors::illustrated-layout')

@section('title', __('errors.serverError'))
@section('code', '500')
@section('message', __('errors.serverError'))
@section('image')
    <div style="background-image: url('/images/hermoso-camino-madera-que-impresionantes-arboles-coloridos-bosque.jpeg');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection
@section('message_custom')
    {{ __('errors.error') }}<br>
    {{ __('errors.error2') }}<br>
    {{ __('errors.error3') }}<br>
    {{ __('errors.error4') }}
@stop
