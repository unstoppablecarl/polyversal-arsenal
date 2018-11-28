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
    Route::get('tiles', 'TileController@index')
        ->name('tiles.index')
        ->middleware('can:view,' . Tile::class);


    Route::view('app', 'app')->name('tiles.app');


    Route::group([
        'middleware' => 'can:create,' . Tile::class,
    ], function () {
        Route::post('tiles', 'TileController@store')
            ->name('tiles.store');
    });

    Route::group([
        'middleware' => 'can:update,tile',
    ], function () {
        Route::get('app#/tile/{tile}', 'TileController@edit')
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

    Route::get('tiles/{tile}', 'TileController@show')
        ->middleware('can:view,tile')
        ->name('tiles.view');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
