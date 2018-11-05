<?php

use App\Models\Tile;

$showUnauthorized = $showUnauthorized ?? true;
$size             = $size ?? '';
$toolTip          = $toolTip ?? null;

if ($size) {
    $size = ' btn-' . $size;
}

$url      = route('tiles.create');
$can      = Gate::allows('create', Tile::class);

$disabled = $can;

?>
@if($can || $showUnauthorized)
    <a href="{{$url}}" class="btn btn-default{{$disabled}}{{$size}}">
        <i class="fa fa-plus"></i> {{$button_text or 'Create Tile'}}
    </a>
@endif
