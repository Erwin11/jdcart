<?php
$is_active = function ($itemid='') use ($data)
    {
        if ( isset($data) && $data->category_id === $itemid)
            return 'selected';
        else
            return '';
    }
?>

@foreach($items as $item)
    @if (isset($item->subs))
        <option class="sub-{{$item->depth}}" value="{{$item->id}}" {{ $is_active($item->id) }}>
            {{$item->option_prefix}} {{$item->name}}
        </option>
        @include('admin.article.subcates', array('items' => $item->subs))
    @else
        <option class="sub-{{$item->depth}}" value="{{$item->id}}" {{ $is_active($item->id) }}>
            {{$item->option_prefix}} {{$item->name}}
        </option>
    @endif
@endforeach