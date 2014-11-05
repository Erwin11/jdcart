<?php
$is_active = function ($name='') use ($activeCategory)
{
    if ($activeCategory && $activeCategory === $name)
        return ' active';
    else
        return '';
}
?>

<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top1" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <!-- <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">切换菜单栏</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
 -->        <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{asset('assets/img/logo-red.png')}}" alt="">
            </a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                @if(isset($categories))
                    @for ($i = 0; $i < 3; $i++)
                    <li class="{{ $is_active($categories[$i]->id) }}">
                         <a href="{{ route('categoryArticlesSlug', $categories[$i]->slug) }}">{{ $categories[$i]->name }}</a>
                    </li>
                    @endfor
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
@if(Auth::guest()){{--游客--}}
                <li{{ $is_active('signin') }}><a href="{{ route('signin') }}">登录</a></li>
                <li{{ $is_active('signup') }}><a href="{{ route('signup') }}">注册</a></li>
@elseif(! Auth::user()->is_admin){{--普通登录用户--}}
                <li><a href="{{ route('account.index') }}">{{ Auth::user()->email }}</a></li>
                <li><a href="{{ route('logout') }}">退出</a></li>
@elseif(Auth::user()->is_admin){{--管理员--}}
                <li><a href="{{ route('admin') }}">{{ Auth::user()->email }}</a></li>
                <li><a href="{{ route('logout') }}">退出</a></li>
                <li class="dropdown" style="display:none;">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ Auth::user()->email }}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('admin') }}">进入后台</a></li>
                        <li><a href="{{ route('account.index') }}">用户中心</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('logout') }}">退出</a></li>
                    </ul>
                </li>
@endif
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>