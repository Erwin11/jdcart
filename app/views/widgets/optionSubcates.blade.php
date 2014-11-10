<?php
$is_active = function ($itemid='') use ($dataid)
    {
        if ( isset($dataid) && $dataid === $itemid)
            return 'selected';
        else
            return '';
    }
?>
{{$dataid}}
@foreach($items as $item)
    @if (isset($item->subs))
        <option class="sub-{{$item->depth}}" value="{{$item->id}}" {{ $is_active($item->id) }}>
            {{$item->catePrefix()}} {{$item->name}}
        </option>
        @include('widgets.optionSubcates', array('items' => $item->subs))
    @else
        <option class="sub-{{$item->depth}}" value="{{$item->id}}" {{ $is_active($item->id) }}>
            {{$item->catePrefix()}} {{$item->name}}
        </option>
    @endif
@endforeach