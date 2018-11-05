@extends('layouts.form-item',
[
    'item_action' => 'View',

    'item_type_name' => 'Tile',
    'item_instance_name' => $item->title,
])

@section('breadcrumbs')
    @component('records.tiles.partials.breadcrumbs', [
        'name' => 'View'
    ])
    @endcomponent
@endsection

@section('form-title')
    @parent
    @include('tiles')
@endsection

@section('controls')
    @include('records.tiles.controls.buttons')
@endsection

@section('content-after')

@endsection
