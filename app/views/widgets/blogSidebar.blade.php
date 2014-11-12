
<?php
$is_active = function ($name='') use ($activeCategory)
{
    if ($activeCategory == $name)
        return ' active';
    else
        return '';
}
?>

<div class="col-xs-4 col-sm-2 sidebar-offcanvas" id="sidebar" role="navigation">
    <div class="list-group">
        <span class="list-group-item"><h4>{{$cateItem->name}}</h4></span>
        @foreach($articles as $article)
        <div class="slide-list-item{{ $is_active($article->id) }}">
            <a class="list-group-item{{ $is_active($article->id) }}" href="{{ route('blog.show', $article->id) }}">{{ $article->title }}</a>
            @if($article->module_extend && count($article->modules)>0)
            <ul class="module-list">
                @foreach ($article->modules as $module)
                <li><a href="#module{{$module->id}}">{{ $module->title }}</a></li>
                @endforeach
            </ul>
            @endif
        </div>
        @endforeach
    </div>
</div><!--/span-->

@section('end')
    @parent
    <script>
        $(document).ready(function() {
            $('[data-toggle=offcanvas]').click(function() {
                $('.row-offcanvas').toggleClass('active');
            });
        });
    </script>
@stop