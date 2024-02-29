<?php
namespace Tests\Unit;

use Orchestra\Testbench\TestCase;
use Rkcreative\AwsImageHandler\AwsImageHandlerServiceProvider;
use Rkcreative\AwsImageHandler\Services\ImageHandler;

class AwsImageHandlerServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set the configuration values for the tests
        config([
            'aws-image-handler.distributionUrl' => 'your-test-distribution-url',
            'aws-image-handler.defaultBucket'   => 'your-test-default-bucket',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [AwsImageHandlerServiceProvider::class];
    }

    public function testRegister()
    {
        $this->assertTrue($this->app->bound('imagehandler'));
        $this->assertInstanceOf(ImageHandler::class, $this->app->make('imagehandler'));
    }

    public function testBoot()
    {
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
            $this->assertTrue(ImageHandler::hasMacro($macro));
        }
    }
}
