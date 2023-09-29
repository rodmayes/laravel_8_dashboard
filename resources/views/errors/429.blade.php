@extends('errors::illustrated-layout')

@section('code', '429 😭')
@section('title', __('errors.tooManyRequest'))
@section('message', __('errors.tooManyRequest'))
@section('image')
    <div style="background-image: url('/images/hermoso-camino-madera-que-impresionantes-arboles-coloridos-bosque.jpeg');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection
@section('message_custom')
    {{ __('errors.tooManyPetitions') }}<br>
@stop
