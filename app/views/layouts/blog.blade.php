@extends('layouts.base')

@section('title') Simple - Blog @parent @stop

@section('beforeStyle')
    {{ style('bootstrap-3.2.0') }}
    {{ HTML::style('assets/css/blog.css') }}
@parent @stop

@section('style')
@parent @stop

@section('body')

    @if(isset($cate_parentid))
        @include('widgets.blogNavbar', array('activeCategory' => $cate_parentid))
    @else
        @include('widgets.blogNavbar', array('activeCategory' => $category_id))
    @endif
    @if(isset($cateSubs))
        @include('widgets.blogSubNavbar', array('activeCategory' => $category_id, 'cateSubs' => $cateSubs))
    @else
        暂无数据
    @endif
    <div class="container">
        @yield('container')
    </div>

@stop

@section('end')
    {{ script('jquery-1.10.2', 'bootstrap-3.2.0') }}
    {{ HTML::script('assets/js/base.js') }}
@parent @stop
