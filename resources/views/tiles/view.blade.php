@extends('layouts.form-item',
[
    'item_action' => 'View',

    'item_type_name' => 'Tile',
    'item_instance_name' => $item->name,
])

@section('breadcrumbs')

@endsection


@section('controls')
    @include('tiles.controls.buttons')
@endsection

@section('form')
    <div id="app-tile-view">
        <app-tile-view
            :tile-id="{{$item->id}}"
        />
    </div>
@endsection
