<?php

Route::get('/', function () {
    return view('index');
});


// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');


use App\Models\Tile;

Route::group([
    'middleware' => 'auth',
], function () {
    Route::group([
        'middleware' => 'can:view,' . Tile::class,
    ], function () {
        Route::get('tiles', 'TileController@index')
            ->name('tiles.index');

        Route::get('tiles-grid', 'TileController@grid')
            ->name('tiles.grid');
    });


    Route::group([
        'middleware' => 'can:create,' . Tile::class,
    ], function () {
        Route::get('app', 'TileController@create')
            ->name('tiles.create');

        Route::post('tiles', 'TileController@store')
            ->name('tiles.store');
    });

    Route::get('tile-sheets/create', 'TileSheetController@create')
        ->name('tile-sheets.create');

    Route::group([
        'middleware' => 'can:update,tile',
    ], function () {
        Route::get('app#/tile/{tile}', 'TileController@edit')
            ->name('tiles.edit');

        Route::put('tiles/{tile}', 'TileController@update')
            ->name('tiles.update');

        Route::put('tiles/{tile}/front-source-image/update', 'TileSourceImageController@updateFront')
            ->name('tiles.front-source-image.update');

        Route::put('tiles/{tile}/back-source-image/update', 'TileSourceImageController@updateBack')
            ->name('tiles.back-source-image.update');

        Route::delete('tiles/{tile}/front-source-image/delete', 'TileSourceImageController@deleteFront')
            ->name('tiles.front-source-image.delete');

        Route::delete('tiles/{tile}/back-source-image/delete', 'TileSourceImageController@deleteBack')
            ->name('tiles.back-source-image.delete');
    });

    Route::group([
        'middleware' => 'can:delete,tile',
    ], function () {
        Route::get('tiles/{tile}/delete', 'TileController@delete')
            ->name('tiles.delete');

        Route::delete('tiles/{tile}/destroy', 'TileController@destroy')
            ->name('tiles.destroy');
    });

    Route::get('tiles/{tile}', 'TileController@show')
        ->middleware('can:view,tile')
        ->name('tiles.view');

    Route::get('tiles/{tile}/print-settings', 'TilePrintSettingsController@show')
        ->middleware('can:view,tile')
        ->name('tiles.print-settings.view');

    Route::put('tiles/{tile}/print-settings', 'TilePrintSettingsController@update')
        ->middleware('can:update,tile')
        ->name('tiles.print-settings.update');
});

Route::view('test', 'test');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
