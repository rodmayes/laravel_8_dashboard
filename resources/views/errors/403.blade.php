@extends('errors::illustrated-layout')

@section('title', __('errors.forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'errors.forbidden'))
@section('image')
    <div style="background-image: url('/images/hermoso-camino-madera-que-impresionantes-arboles-coloridos-bosque.jpeg');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection
@section('message_custom')
    {{ __('errors.unauthorized') }}<br>
    @if(auth()->user())
        @if(auth()->user()->hasRole('staff'))
            @if(!empty(url()->previous()))
                <a href="{{ url()->previous() }}">{{  __('errors.goBack') }}</a>
            @endif
            <br>
            <a href="{{  route('home') }}">{{   __('errors.goHome') }}</a>
        @elseif(auth()->user())
            <a href="{{  route('logout') }}">{{   __('errors.goHome') }}</a>
        @endif
    @endif
    @if($exception->getMessage())
        <h5 class="text-danger">
            {{ $exception->getMessage() }}
        </h5>
    @endif
@stop


