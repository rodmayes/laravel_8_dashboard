@extends('errors::minimal')

@section('title', __('errors.unauthorized2'))
@section('code', '401')
@section('message', __('errors.unauthorized2'))
@section('image')
    <div style="background-image: url('/images/hermoso-camino-madera-que-impresionantes-arboles-coloridos-bosque.jpeg');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection
@section('message_custom')
{{ __('errors.unauthorized') }}<br>
@stop
