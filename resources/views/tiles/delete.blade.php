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

    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h5>Tile Front PNG</h5>
                    @if($front_image_url)
                        <a href="{{$front_image_url}}" class="btn btn-primary" target="_blank">Save PNG</a>
                    @endif
                </div>
                <div class="card-body">
                    @if($front_image_url)
                        <img src="{{$front_image_url}}" class="img-fluid"/>
                    @else
                        <h5 class="text-danger">Not generated</h5>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h5>Tile Front SVG</h5>
                    @if($front_svg_url)
                        <a href="{{$front_svg_url}}" class="btn btn-primary" target="_blank">Save SVG</a>
                    @endif
                </div>
                <div class="card-body">
                    @if($front_svg_url)
                        <img src="{{$front_svg_url}}" class="img-fluid"/>
                    @else
                        <h5 class="text-danger">Not generated</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h5>Tile Back PNG</h5>
                    @if($back_image_url)
                        <a href="{{$back_image_url}}" class="btn btn-primary" target="_blank">Save PNG</a>
                    @endif
                </div>
                <div class="card-body">
                    @if($back_image_url)
                        <img src="{{$back_image_url}}" class="img-fluid"/>
                    @else
                        <h5 class="text-danger">Not generated</h5>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h5>Tile Back SVG</h5>
                    @if($back_svg_url)
                        <a href="{{$back_svg_url}}" class="btn btn-primary" target="_blank">Save SVG</a>
                    @endif
                </div>
                <div class="card-body">
                    @if($back_svg_url)
                        <img src="{{$back_svg_url}}" class="img-fluid"/>
                    @else
                        <h5 class="text-danger">Not generated</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
