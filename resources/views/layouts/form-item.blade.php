@extends('layouts.app')

@section('content')

@section('form-header')
    <div class="row">
        <div class="col-sm-8">
            @section('form-title')

                <h2 class="admin-form-title">
                    @section('form-title-text')
                        @if(!empty($item_action))
                            <span class="text-muted">{{$item_action}}</span>
                        @endif
                        <strong>
                            {{$item_type_name OR ''}}@if(!empty($item_instance_name)):@endif
                        </strong>
                        {{$item_instance_name OR ''}}
                    @show
                </h2>

                @hasSection('item-subtitle')
                    <h4>@yield('item-subtitle')</h4>
                @endif

            @show
        </div>
        <div class="col-sm-4">
            <div class="pull-right">
                @yield('controls')
            </div>
        </div>
    </div>
    <hr>
@show


@yield('form')


@endsection
