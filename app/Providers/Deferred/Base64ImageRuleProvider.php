<?php

namespace App\Providers\Deferred;


use App\Services\Validation\Base64ImageRule;
use Illuminate\Support\ServiceProvider;

class Base64ImageRuleProvider extends ServiceProvider
{
    public $defer = true;

    public function register()
    {
        $this->app->bind(Base64ImageRule::class, function () {
            $maxSizeInMegaBytes = config('image-uploads.max_file_size_mb');
            $validFileFormats   = config('image-uploads.valid_file_formats');
            return new Base64ImageRule($maxSizeInMegaBytes, $validFileFormats);
        });
    }

    public function provides()
    {
        return [Base64ImageRule::class];
    }
}
