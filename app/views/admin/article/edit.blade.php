@extends('layouts.admin', array('active' => $resource))

@section('title') @parent 编辑{{ $resourceName }} @stop

@section('beforeStyle')
  @parent
  {{ style('bootstrap-markdown') }}
  {{ HTML::style('assets/css/admin/edit-article.css') }}
@stop


@section('container')

  @include('widgets.notification')
  <h3>
    编辑{{ $resourceName }}
    <div class="pull-right">
      <a href="{{ route($resource.'.index') }}" class="btn btn-sm btn-default">&laquo; 返回{{ $resourceName }}列表</a>
    </div>
  </h3>

  <ul class="nav nav-tabs">
    <li class="active">
      <a href="#tab-general" data-toggle="tab">主要内容</a>
    </li>
    <li>
      <a href="#tab-module" data-toggle="tab">模块内容</a>
    </li>
    <li>
      <a href="#tab-meta-data" data-toggle="tab">SEO</a>
    </li>
    <li>
      <a href="#tab-info" data-toggle="tab">文章相关信息</a>
    </li>
  </ul>

  <form class="form-horizontal" method="post" action="{{ route($resource.'.update', $data->id) }}" autocomplete="off" style="background:#f8f8f8;padding:1em;border:1px solid #ddd;border-top:0;">
    <!-- CSRF Token -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="_method" value="PUT" />


    <!-- Tabs Content -->
    <div class="tab-content">

      <!-- General tab -->
      <div class="tab-pane active" id="tab-general" style="margin:0 1em;">

        <div class="form-group">
          <label for="category">分类</label>
          {{ $errors->first('category', '
          <span style="color:#c7254e;margin:0 1em;">:message</span>
          ') }}
          {{ Form::select('category', $categoryLists, $data->category->id, array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
          <label for="title">标题</label>
          {{ $errors->first('title', '
          <span style="color:#c7254e;margin:0 1em;">:message</span>
          ') }}
          <input class="form-control" type="text" name="title" id="title" value="{{ Input::old('title', $data->title) }}" /></div>

        <div class="form-group">
          <label for="slug">Slug</label>
          {{ $errors->first('slug', '
          <span style="color:#c7254e;margin:0 1em;">:message</span>
          ') }}
          <div class="input-group">
            <span class="input-group-addon" >{{ str_finish(URL::to('/'), '/') }}</span>
            <input class="form-control" type="text" name="slug" id="slug" value="{{ Input::old('slug', $data->slug) }}"></div>
        </div>

        <div class="form-group">
          <label for="content">内容</label>
          {{ $errors->first('content', '
          <span style="color:#c7254e;margin:0 1em;">:message</span>
          ') }}
          <textarea id="content" class="form-control" data-provide="markdown" name="content" rows="10">{{ Input::old('content', $data->content_markdown) }}</textarea>
        </div>
      </div>

      <!-- Module tab -->
      <div class="tab-pane" id="tab-module" style="margin:0 1em;">
        <ul class="module-list">
          @foreach($data->modules as $module)
          <li>
            <h4>{{$module->title}}</h4>
            <div class="opt">
              <a class="glyphicon glyphicon-edit" title="编辑">edit</a>
              <a class="glyphicon glyphicon glyphicon-trash" title="删除">delete</a>
            </div>
            @endforeach
          </li>
          <li class="module-add">
            <div class="glyphicon glyphicon-plus"></div>
          </li>
        </ul>


        
      </div>

      <!-- Meta Data tab -->
      <div class="tab-pane" id="tab-meta-data" style="margin:0 1em;">

        <div class="form-group">
          <label for="meta_title">Meta Title</label>
          <input class="form-control" type="text" name="meta_title" id="meta_title" value="{{ Input::old('meta_title', $data->meta_title) }}" /></div>

        <div class="form-group">
          <label for="meta_description">Meta Description</label>
          <input class="form-control" type="text" name="meta_description" id="meta_description" value="{{ Input::old('meta_description', $data->meta_description) }}" /></div>

        <div class="form-group">
          <label for="meta_keywords">Meta Keywords</label>
          <input class="form-control" type="text" name="meta_keywords" id="meta_keywords" value="{{ Input::old('meta_keywords', $data->meta_keywords) }}" /></div>

      </div>

      <!-- Info tab -->
      <div class="tab-pane" id="tab-info" style="margin:0 1em 2em 1em;">

        <div class="form-group">
          <label>作者</label>
          <p class="form-control-static">{{ $data->user ? $data->user->email : '作者信息丢失' }}</p>
        </div>

        <div class="form-group">
          <label>创建时间</label>
          <p class="form-control-static">{{ $data->created_at }}（{{ $data->friendly_created_at }}）</p>
        </div>

        <div class="form-group">
          <label>最后修改时间</label>
          <p class="form-control-static">{{ $data->updated_at }}（{{ $data->friendly_updated_at }}）</p>
        </div>

      </div>

    </div>

    <!-- Form actions -->
    <div class="control-group control-group-tab">
      <div class="controls">
        <a class="btn btn-default" href="{{ route($resource.'.edit', $data->id) }}">重 置</a>
        <button type="submit" class="btn btn-success">提 交</button>
      </div>
    </div>
  </form>

  <!-- Form module edit area -->
  <div id="J_formModule" class="form-module">
    <div class="form-con">
      <form class="form-horizontal" action="">
        <!-- hidden input -->
        <input type="hidden" name="id" value="{{$data->id}}" />
        <!-- from content -->
        <div class="form-group">
          <label for="module_title">模块标题</label>
          <input class="form-control" type="text" name="module_title" id="module_title" value="{{ Input::old('meta_title', $data->meta_title) }}" />
        </div>
        <div class="form-group">
          <label for="module_type">模块类型</label>
          <select id="J_moduleType" class="form-control" name="module_type">
            <option value="txtimg">左文右图</option>
            <option value="txt">纯文字</option>
            <option value="img">纯图片</option>
          </select>
        </div>
        <div class="form-info">
          <div class="form-group form-txt">
            <label for="module_content">模块内容</label>
            <textarea id="module_content" class="form-control" data-provide="markdown" name="module_content" rows="10">{{ Input::old('module_content', $data->meta_title) }}</textarea>
          </div>
          <div class="form-group form-img">
            <label for="module_image">模块图片</label>
            {{ Form::file('module_image', array('class' => 'file_image')) }}
            <button class="btn btn-primary btn-upload btn-sm" type="submit">上传图片</button>
          </div>
        </div>
        <div class="form-group" style="display:none;">
          <label for="module_donwload">模块下载文件</label>
          <input class="form-control" type="text" name="module_donwload" id="module_donwload" value="{{ Input::old('meta_title', $data->meta_title) }}" />
        </div>

        <div class="control-group">
          <div class="controls">
            <a class="btn btn-default">取消</a>
            <a class="btn btn-success">提 交</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@stop

@section('end')
  @parent
  {{ script('markdown', 'to-markdown', 'bootstrap-markdown') }}

  {{ HTML::script('assets/js/base.js') }}
  {{ HTML::script('assets/js/editArticalModule.js') }}
  <!-- test -->
  <div id="J_test" style="display:none;">
    <div class="txt">这是测试文字</div>
    <a class="btn btn-default">测试</a>
  <script>
  $(function(){
    $('#J_test .btn').on('click', function(e){
      e.preventDefault();
      var data = $('.form-horizontal').serialize();
      // data = {b1:333,b2:222};
      $.post('/admin/articles/testdata', data, function(data){
        console.log(data);
      });
    });
  });
  </script>
  </div>



@stop