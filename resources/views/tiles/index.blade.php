@extends('layouts.form-item',
[
    'item_action' => 'List',
    'item_type_name' => 'Tiles',
])

@section('breadcrumbs')
    @component('tiles')
    @endcomponent
@endsection

@section('controls')
    @component('records.tiles.controls.buttons-create')
    @endcomponent
@endsection

@section('form')

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->name}}</td>

                <td class="nowrap" >
                    @include('tiles.controls.buttons', ['item' => $item])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
