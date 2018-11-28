<?php

use App\Models\Tile;

$showUnauthorized = $showUnauthorized ?? true;
$size             = $size ?? '';
$toolTip          = $toolTip ?? null;

if ($size) {
    $size = ' btn-' . $size;
}

$url      = route('tiles.delete',  $item);
$can      = Gate::allows('delete', $item);

$disabled = $can ? '' : ' disabled';

?>
@if($can || $showUnauthorized)
    <a href="{{$url}}" class="btn btn-danger{{$disabled}}{{$size}}">
        Delete
    </a>
@endif
