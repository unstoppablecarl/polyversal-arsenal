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


    @include('tiles.partials.view-tile', ['item' => $item])
@endsection
