@extends('layouts.base')

@section('title') 后台 @parent @stop

@section('beforeStyle')
    {{ style('bootstrap-3.2.0') }}
@parent @stop

@section('style')
body
{
    padding-top:5em;
    padding-bottom: 0;
    background-color: #f3f3ff;
}
@parent @stop

@section('body')

    @include('widgets.adminNavbar')

    <div class="container panel" style="padding-bottom:1em;">
        @yield('container')
    </div>

@stop


@section('end')
    {{ script('jquery-1.10.2', 'bootstrap-3.2.0') }}
@parent @stop
