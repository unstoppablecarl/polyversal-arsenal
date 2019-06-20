<?php

use App\Models\Tile;

$showUnauthorized = $showUnauthorized ?? true;
$size             = $size ?? '';
$toolTip          = $toolTip ?? null;
$buttonText      = $buttonText ?? 'Create Tile';
if ($size) {
    $size = ' btn-' . $size;
}

$url = route('tiles.create');
$can = Gate::allows('create', Tile::class);

$disabled = $can ? '' : ' disabled';

?>
@if($can || $showUnauthorized)
    <a href="{{$url}}" class="btn btn-primary{{$disabled}}{{$size}}">
        {{$buttonText}}
        <i class="fas fa-plus"></i>
    </a>
@endif
