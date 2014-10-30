@extends('layouts.blog', array('active' => 'home'))

@section('description'){{ $article->description }} @stop
@section('keywords'){{ $article->keywords }} @stop
@section('title'){{ $article->title }} - @parent @stop

@section('beforeStyle')
  @parent
  {{ HTML::style('assets/css/article.css') }}
@stop

@section('container')
    
    <div id="J_blogShow" class="row row-offcanvas row-offcanvas-right">
        <span class="left-bg"></span>
        
        @include('widgets.blogSidebar', array('activeCategory' => $article->id))

        <div class="col-xs-8 col-sm-10 main-content">
            <div class="row">
                <div class="col-6 col-sm-6 col-lg-12 panel">
                    <div class="module-content">
                    @foreach($article->modules as $module)
                      <div class="module-item module-{{$module->id}} module-{{$module->type}}">
                        <div class="module-title clearfix">
                            <h4>{{$module->title}}</h4>
                            @if (isset($module->download))
                                <a class="download-link" href="{{$module->download}}"><i></i>下载文件</a>
                            @endif
                        </div>
                        @if ($module->type == 'txtimg')
                            <!-- 左文右图 -->
                            <div class="module-txt">{{ $module->content_html }}</div>
                            <div class="module-pic"><img src="{{$module->image}}" alt=""></div>
                        @elseif ($module->type == 'img')
                            <!-- 整图 -->
                            @if (isset($module->image))
                                <div class="module-pic"><img src="{{$module->image}}" alt=""></div>
                            @endif
                        @else
                            <!-- 整文   -->
                            <div class="module-txt">{{ $module->content_html }}</div>
                        @endif
                      </div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            
            @if (count($article->modules)==0)
               <!-- row -->
                <div class="row">
                    <div class="col-6 col-sm-6 col-lg-12 panel">
                        <h2>{{ $article->title }}</h2>
                        <hr />
                        <p>{{ $article->content_html }}</p>
                        
                        <a name="comments"></a>
                        <p>
                            <i class="glyphicon glyphicon-calendar"></i><span> {{ $article->created_at }}（{{ $article->friendly_created_at }}）</span>
                        </p>
                    </div><!--/span-->

                    <div class="col-6 col-sm-6 col-lg-12 panel" style="display:none;">
                        <h4>评论 - {{ $article->comments_count }}</h4>
                        <ul class="media-list">
                            <?php $article->load('comments.user') ?>
                            @foreach($article->comments as $comment)
                            <li class="media">
                                <a class="pull-left" href="#">
                                    <img class="media-object img-thumbnail" width="64" height="64" src="{{ $comment->user->portrait_small }}" alt="头像（小）">
                                </a>
                                <div class="media-body well well-sm">
                                    <h5 class="media-heading">{{ $comment->user->email }}
                                        <small class="pull-right">发表于：{{ $comment->friendly_created_at }}</small>
                                    </h5>
                                    {{ $comment->content }}
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @include('widgets.notification')
                        @if(Auth::check())
                        <form class="form-horizontal" method="post" autocomplete="off">
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                            <textarea name="content" class="form-control" rows="3">{{ Input::old('content') }}</textarea>
                            {{ $errors->first('content', '<span style="color:#c7254e;margin-top:1em;">:message</span>') }}
                            <button type="submit" class="btn btn-success pull-right" style="margin:1em 0;">发表评论</button>
                        </form>
                        @else
                        <div class="form-horizontal">
                            <div class="form-control" style="height:5em">
                                <a class="btn btn-primary" href="{{ route('signin') }}">登录</a>
                                <a class="btn btn-success" href="{{ route('signup') }}">注册</a>
                            </div>
                            <button class="btn btn-defaut pull-right" style="margin:1em 0;">发表评论</button>
                        </div>
                        @endif
                    </div><!--/span-->

                </div><!--/row-->
            </div><!--/span-->
            @endif
        
        </div><!--/row-->    
    

@stop
