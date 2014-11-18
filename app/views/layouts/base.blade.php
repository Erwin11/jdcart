<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>JDC - @section('title')
        @show{{-- 页面标题 --}}</title> 
  <meta name="description" content="@yield('description')">
  {{-- 页面描述 --}}
  <meta name="keywords" content="@yield('keywords')" />
  {{-- 页面关键词 --}}
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  {{-- token --}}
  <meta name="_token" content="{{ csrf_token() }}" />
  @section('beforeStyle')
    @show{{-- 页面内联样式之前 --}}
  <style>
  @section('style')
  @show{{-- 累加的页面内联样式 --}}
  </style>
  @section('afterStyle')
    @show{{-- 页面内联样式之后 --}}
</head>
<body>
  @yield('body')
    
    @section('end')
    @show{{-- 页面主体之后 --}}
</body>
</html>