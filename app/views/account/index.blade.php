@extends('layouts.account', array('active' => 'account'))

@section('container')

    <div style="height:30em;">
        <h1 style="margin-top:2em; text-align:center;">JDC - 用户中心首页</h1>
        <p style="text-align:center;">这里是JDC规范平台的管理员后台，负责整个JDC规范平台的资源管理。</p>
        @if(method_exists('AuthorityController', 'test')) {{ App::make('AuthorityController')->test() }} @endif
    </div>

@stop
