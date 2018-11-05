<?php

use App\Models\Tile;

$showUnauthorized = $showUnauthorized ?? true;
$size             = $size ?? '';
$toolTip          = $toolTip ?? null;

if ($size) {
    $size = ' btn-' . $size;
}

$url      = route('tiles.delete');
$can      = Gate::allows('delete', Tile::class);

$disabled = $can;

?>
@if($can || $showUnauthorized)
    <a href="{{$url}}" class="btn btn-default{{$disabled}}{{$size}}">
        Delete
    </a>
@endif
