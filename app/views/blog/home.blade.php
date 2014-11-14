@extends('layouts.base', array('active' => 'home')) 

@section('title')规范平台 @parent @stop

@section('beforeStyle')
  {{ HTML::style('assets/css/base.css') }}
  {{ HTML::style('assets/css/home.css') }}
@parent @stop


@section('body')
  <div class="container">
    <div class="content">
      <div class="logo">
        <img src="{{ URL::asset('assets/img/logo.png') }}" alt="JDC - 规范平台">
      </div>
      <div class="nav">
        <ul class="clearfix">
          @for ($i = 0; $i < 3; $i++)
          <li>
              <a href="{{ route('categoryArticlesSlug', $categories[$i]->slug) }}">
              <i class="icon-circle">
                <span class="zh">{{ $categories[$i]->name }}</span>
                <span class="en">{{ $categories[$i]->enname }}</span>
              </i>
              <i class="icon-circle icon-hover">{{ $categories[$i]->abbr }}</i>
            </a>
          </li>
          @endfor
        </ul>
      </div>
    </div>
    <div class="footer">
      <p class="zh">各位亲爱的上海设计中心的小伙伴们<br>此平台规范适用于上海设计中心的各个项目中，请大家严格按照规范输出，<br>遵循规范中的基本准则以保证小组内项目的一致性，共同维护更新。</p>
      <p class="en">Copyright © 2014 JDC. All rights reserved</p>
    </div>
  </div>
@stop

@section('end')
  {{ script('jquery-1.10.2') }}
<script>
  
</script>
@parent @stop
<!-- 特殊首页，未继承layouts.blog -->