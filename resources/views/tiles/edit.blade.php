@extends('layouts.form-item',
[
    'item_action' => 'Edit',
    'item_type_name' => 'Tile',

    'item_instance_name' => $item->title,
])

@section('breadcrumbs')
@endsection

@section('controls')
    @include('tiles.controls.buttons')
@endsection

@section('form')


    {!! Form::model( $item, ['url' => route('tiles.update', $item), 'method' => 'put', 'class' => 'form-horizontal']) !!}

      @include('form.field-input', ['key' => 'name', 'required' => true])

    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit( 'Update', ['class' => 'btn btn-large btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    <div id="app">
        <tile>
        </tile>
    </div>

@endsection
