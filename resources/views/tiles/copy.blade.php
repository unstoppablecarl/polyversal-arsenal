@extends('layouts.form-item',
[
    'item_action' => 'Copy',

    'item_type_name' => 'Tile',
    'item_instance_name' => $item->name,
])

@section('breadcrumbs')

@endsection


@section('controls')
    @include('tiles.controls.buttons')
@endsection

@section('form')
    <form method="post" action="/tiles/{{$item->id}}/copy">
        @csrf
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tile Copy Name</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="name" value="{{$item->name}} Copy"/>
            </div>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-primary">Copy Tile</button>
            </div>
        </div>

    </form>
@endsection

