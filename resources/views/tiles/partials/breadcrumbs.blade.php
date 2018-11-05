<?php
$breadcrumbs = $breadcrumbs ?? [];
$name = $name ?? false;
$baseBreadcrumbs = [
    '/tiles' => 'tiles',
];

$breadcrumbs = array_merge($baseBreadcrumbs, $breadcrumbs);

if($name){
    $breadcrumbs['-'] = $name;
}

?>

@component('layouts.partials.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
@endcomponent
