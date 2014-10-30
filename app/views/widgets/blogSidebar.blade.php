
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
    <p class="visible-xs" style="margin:-1.3em -1em 0 -5.2em;">
        <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">== ==</button>
    </p>
    <div class="list-group">
        <!-- <span class="list-group-item"><h4>文章分类</h4></span> -->
        @foreach($articles as $article)
        <a class="list-group-item{{ $is_active($article->id) }}" href="{{ route('blog.show', $article->slug) }}">{{ $article->title }}</a>
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