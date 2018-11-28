<?php

$useBtnGroup = $useBtnGroup ?? true;
$size        = $size ?? '';

if ($size) {
    $size = ' btn-group-' . $size;
}
?>

@if($useBtnGroup)
    <div class="btn-group {{$size}}">
        @endif

        @include('tiles.controls.buttons-view', ['item' => $item])
        @include('tiles.controls.buttons-edit', ['item' => $item])
        @include('tiles.controls.buttons-delete', ['item' => $item])

        @if($useBtnGroup)
    </div>
@endif
