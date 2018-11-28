<?php

namespace App\Providers;

use Barryvdh\Debugbar\Middleware\DebugbarEnabled;
use Barryvdh\Debugbar\ServiceProvider;

class DebugbarServiceProvider extends ServiceProvider
{
    /** copied from parent */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/debugbar.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        $routeConfig = [
            'namespace'  => 'Barryvdh\Debugbar\Controllers',
            'prefix'     => $this->app['config']->get('debugbar.route_prefix'),
            'domain'     => $this->app['config']->get('debugbar.route_domain'),
            // change 1: adding web middleware
            'middleware' => ['web', DebugbarEnabled::class],
        ];

        $this->getRouter()->group($routeConfig, function ($router) {
            $router->get('open', [
                'uses' => 'OpenHandlerController@handle',
                'as'   => 'debugbar.openhandler',
            ]);

            $router->get('clockwork/{id}', [
                'uses' => 'OpenHandlerController@clockwork',
                'as'   => 'debugbar.clockwork',
            ]);

            $router->get('assets/stylesheets', [
                'uses' => 'AssetController@css',
                'as'   => 'debugbar.assets.css',
            ]);

            $router->get('assets/javascript', [
                'uses' => 'AssetController@js',
                'as'   => 'debugbar.assets.js',
            ]);

            $router->delete('cache/{key}/{tags?}', [
                'uses' => 'CacheController@delete',
                'as'   => 'debugbar.cache.delete',
            ]);
        });

        // change 2: remove InjectDebugbar middleware
        // it will be added to the web middleware group in App\Http\Kernel

        // $this->registerMiddleware(InjectDebugbar::class);
    }
}
