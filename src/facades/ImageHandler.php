<?php
namespace Rkcreative\AwsImageHandler\Facades;

use Illuminate\Support\Facades\Facade;

class ImageHandler extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'imagehandler';
    }
}
