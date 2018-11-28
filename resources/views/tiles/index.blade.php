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

@section('form')

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Class</th>
            <th>Mobility</th>
            <th>Tech Level</th>
            <th>Move</th>
            <th>Evasion</th>
            <th>Armor</th>
            <th>Targeting</th>
            <th>Assault</th>
            <th>Cost</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->tile_type}}</td>
                <td>{{$item->tile_class}}</td>
                <td>{{$item->tile_mobility}}</td>
                <td>{{$item->tech_level}}</td>
                <td>{{$item->move}}</td>
                <td>{{$item->evasion}}</td>
                <td>{{$item->armor}}</td>
                <td>{{$item->targeting_id}}</td>
                <td>{{$item->assault_id}}</td>
                <td>{{$item->cached_cost}}</td>

                <td class="nowrap" >
                    @include('tiles.controls.buttons', ['item' => $item->model])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
