<?php
$is_active = function ($itemid='') use ($dataid)
    {
        if ( isset($dataid) && $dataid === $itemid)
            return 'active';
        else
            return '';
    }
?>
{{$dataid}}
@foreach($items as $item)
    @if (isset($item->subs))
        <li><a href='{{$route}}?category={{$item->id}}'>{{$item->catePrefix()}} {{$item->name}}</a></li>
        @include('widgets.listSubcates', array('items' => $item->subs))
    @else
        <li><a href='{{$route}}?category={{$item->id}}'>{{$item->catePrefix()}} {{$item->name}}</a></li>
        <!-- <option class="sub-{{$item->depth}}" value="{{$item->id}}" {{ $is_active($item->id) }}>
            {{$item->catePrefix()}} {{$item->name}}
        </option> -->
    @endif
@endforeach