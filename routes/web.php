<?php

Route::get('/', function () {
    return view('index');
});

Auth::routes();

use App\Models\Tile;

Route::get('tiles', 'TileController@index')
    ->name('tiles.index')
    ->middleware('can:view,' . Tile::class);

Route::get('tiles/{tile}/view', 'TileController@show')
    ->middleware('can:view,tile')
    ->name('tiles.view');

Route::group([
    'middleware' => 'can:create,' . Tile::class,
], function () {

    Route::get('tiles/create', 'TileController@create')
        ->name('tiles.create');

    Route::post('tiles', 'TileController@store')
        ->name('tiles.store');
});

Route::group([
    'middleware' => 'can:update,tile',
], function () {

    Route::get('tiles/{tile}/edit', 'TileController@edit')
        ->name('tiles.edit');

    Route::put('tiles/{tile}', 'TileController@update')
        ->name('tiles.update');
});

Route::group([
    'middleware' => 'can:delete,tile',
], function () {
    Route::get('tiles/{tile}/delete', 'TileController@delete')
        ->name('tiles.delete');

    Route::delete('tiles/{tile}/destroy', 'TileController@destroy')
        ->name('tiles.destroy');
});
