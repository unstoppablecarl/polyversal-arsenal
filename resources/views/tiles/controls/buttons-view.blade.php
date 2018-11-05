<?php

$url      = route('tiles.view', $item->id);
$can      = Gate::check('view', $item);
$disabled = $can ? '' : ' disabled';

?>

<a href="{{$url}}" class="btn btn-default{{$disabled}}">
    View
</a>
