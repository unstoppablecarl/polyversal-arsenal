@extends('layouts.form-item',
[
    'item_action' => 'Edit',
    'item_type_name' => 'Tile',

    'item_instance_name' => $item->title,
])

@section('breadcrumbs')
    @component('records.tiles.partials.breadcrumbs', [
        'name' => 'Edit'
    ])
    @endcomponent
@endsection

@section('controls')
    @include('tiles')
@endsection

@section('form-title')
    @parent
    @include('records.tiles.controls.title-details')
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

@endsection
