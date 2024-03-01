<?php
namespace Rkcreative\AwsImageHandler\Tests;

use Orchestra\Testbench\TestCase;
use Rkcreative\AwsImageHandler\Services\ImageHandler;

class MacroTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \Rkcreative\AwsImageHandler\AwsImageHandlerServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Set the configuration values for the tests
        config([
            'aws-image-handler.distributionUrl' => 'your-test-distribution-url',
            'aws-image-handler.defaultBucket'   => 'your-test-default-bucket',
        ]);
    }

    public function testResizeMacro()
    {
        $imageHandler = new ImageHandler();

        // Call the macro through the ImageHandler instance
        $imageHandler->resize(200, 200, 'cover', 'black');

        $this->assertEquals([
            'resize' => [
                'width'      => 200,
                'height'     => 200,
                'fit'        => 'cover',
                'background' => 'black'
            ]
        ], $imageHandler->getOptions());
    }
}
