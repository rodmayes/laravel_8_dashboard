@extends('errors::illustrated-layout')

@section('title', __('errors.invalidRequest'))
@section('code', '400')
@section('message', __('errors.invalidRequest'))
@section('image')
    <div style="background-image: url('/images/hermoso-camino-madera-que-impresionantes-arboles-coloridos-bosque.jpeg');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection
@section('message_custom')
    {{ __('errors.invalidRequest') }}<br>
@stop
