@extends('layouts.app', ['bodyClass' => 'body-tile-sheet-print'])

@section('head-end')
@endsection

@section('content')
    <div class="row no-print">
        <div class="col-sm-8">
            <h1>
                Tile Sheet
                <strong>
                    Creator
                </strong>
            </h1>
        </div>
    </div>
@endsection

@section('content-after')
    <div id="app-tile-sheet-create"></div>
@endsection

