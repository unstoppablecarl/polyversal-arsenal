@extends('layouts.form-item',
[
    'item_action' => 'Listing',
    'item_type_name' => 'Your Tiles',
])

@section('breadcrumbs')

@endsection

@section('controls')
    @component('tiles.controls.buttons-create')
    @endcomponent
@endsection

@section('content-after')
    <div id="app-tile-grid"></div>
@endsection
