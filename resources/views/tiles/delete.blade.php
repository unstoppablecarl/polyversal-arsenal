@extends('layouts.form-item',
[
    'item_action' => 'Delete',

    'item_type_name' => 'Tile',
    'item_instance_name' => $item->name,
])

@section('breadcrumbs')

@endsection

@section('controls')
    @include('tiles.controls.buttons')
@endsection

@section('form')


    <div class="float-right">
    {!! Form::model( $item, ['url' => route('tiles.destroy', $item), 'method' => 'delete']) !!}

        <button type="submit" class="btn btn-danger">
            Delete Tile
            <i class="fas fa-fw fa-trash"></i>
        </button>
    {!! Form::close() !!}
    </div>
    <h2 class="text-danger">Are you sure you want to delete this Tile?</h2>

    @include('tiles.partials.view-tile', ['item' => $item])

@endsection
