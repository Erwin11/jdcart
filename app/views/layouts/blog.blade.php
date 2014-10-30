@extends('layouts.base')

@section('title') Simple - Blog @parent @stop

@section('beforeStyle')
    {{ style('bootstrap-3.2.0') }}
    {{ HTML::style('assets/css/blog.css') }}
@parent @stop

@section('style')
@parent @stop

@section('body')

    @include('widgets.blogNavbar', array('activeCategory' => $category_id))

    <div class="container">
        @yield('container')
    </div>

@stop

@section('end')
    {{ script('jquery-1.10.2', 'bootstrap-3.2.0') }}
    {{ HTML::script('assets/js/base.js') }}
@parent @stop
