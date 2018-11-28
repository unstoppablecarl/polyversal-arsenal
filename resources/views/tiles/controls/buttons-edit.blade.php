<?php

$showUnauthorized = $showUnauthorized ?? true;
$size             = $size ?? '';
$toolTip          = $toolTip ?? null;

if ($size) {
    $size = ' btn-' . $size;
}

$url      = route('tiles.edit', $item);
$can      = Gate::allows('update', $item);

$disabled = $can ? '' : ' disabled';

?>
@if($can || $showUnauthorized)
    <a href="{{$url}}" class="btn btn-light{{$disabled}}{{$size}}">
        Edit
    </a>
@endif
