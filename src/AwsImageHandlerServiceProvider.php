<?php
namespace Rkcreative\AwsImageHandler;

use Illuminate\Support\ServiceProvider;
use Rkcreative\AwsImageHandler\Services\ImageHandler;

class AwsImageHandlerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('imagehandler', function ($app) {
            return new ImageHandler();
        });

        $this->app->alias('imagehandler', 'Rkcreative\AwsImageHandler\Facades\ImageHandler');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/aws-image-handler.php' => config_path('aws-image-handler.php'),
        ], 'config');

        $macros = [
            'smartCrop',
            'roundCrop',
            'contentModeration',
            'crop',
            'quality',
            'resize',
            'setRgba',
            'rotate',
        ];

        foreach ($macros as $macro) {
            ImageHandler::macro($macro, function (...$args) use ($macro) {
                $macroFunction = require __DIR__ . "/Macros/{$macro}.php";

                return $macroFunction(...$args);
            });
        }
    }
}
