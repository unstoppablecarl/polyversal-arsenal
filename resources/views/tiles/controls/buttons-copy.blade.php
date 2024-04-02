<?php

$showUnauthorized = $showUnauthorized ?? true;
$size             = $size ?? '';
$toolTip          = $toolTip ?? null;

if ($size) {
    $size = ' btn-' . $size;
}

$url      = route('tiles.copy', $item);
$can      = Gate::allows('copy', $item);

$disabled = $can ? '' : ' disabled';

?>
@if($can || $showUnauthorized)
    <a href="{{$url}}" class="btn btn-light{{$disabled}}{{$size}}">
        Copy
    </a>
@endif
