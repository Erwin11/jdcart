<?php
$is_active = function ($name='') use ($activeCategory)
{
    if ($activeCategory == $name)
        return ' active';
    else
        return '';
}
?>

<div class="subnav">
    <ul>
        @foreach ($cateSubs as $cate)
            <li class="{{ $is_active($cate->id) }}">
                <a href="{{ route('categorySubArticles', $cate->id) }}">
                    <span class="icon_con"><i class="icon icon_{{$cate->slug}}"></i></span>
                    <span class="title">{{$cate->name}}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>