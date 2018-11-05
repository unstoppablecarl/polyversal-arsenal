@extends('layouts.form-item',
[
    'item_action' => 'Delete',

    'item_type_name' => 'Tile',
    'item_instance_name' => $item->title,
])

@section('breadcrumbs')
    @component('records.tiles.partials.breadcrumbs', [
        'name' => 'Delete'
    ])
    @endcomponent
@endsection

@section('form-title')
    @parent
    @include('records.tiles.controls.title-details')
@endsection

@section('controls')
    @include('records.tiles.controls.buttons')
@endsection

@section('form')

    <h2 class="text-danger">Are you sure you want to delete this Tile?</h2>

    {!! Form::model( $item, ['url' => route('tiles.destroy', $item), 'method' => 'delete']) !!}

        {!! Form::submit( 'Delete', ['class' => 'btn btn-large btn-danger']) !!}

    {!! Form::close() !!}

@endsection
