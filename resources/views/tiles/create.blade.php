@extends('layouts.form-item',
[
    'item_action' => 'Create',
    'item_type_name' => 'Tile',
])

@section('breadcrumbs')
    @component('tiles.partials.breadcrumbs', [
        'name' => 'Create'
    ])
    @endcomponent
@endsection

@section('form')

    {!! Form::open(['url' => route('tiles.store'), 'class' => 'form-horizontal']) !!}

    @include('form.field-input', ['key' => 'name', 'required' => true])


    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit( 'Create', ['class' => 'btn btn-large btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}
@endsection
