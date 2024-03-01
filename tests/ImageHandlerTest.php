<?php
namespace Rkcreative\AwsImageHandler\Tests;

use Orchestra\Testbench\TestCase;
use Rkcreative\AwsImageHandler\Services\ImageHandler;
use TypeError;

class ImageHandlerTest extends TestCase
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

    public function testResizeWithValidInput()
    {
        $imageHandler = new ImageHandler();
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

    public function testSmartCropWithArray()
    {
        $imageHandler = new ImageHandler();
        $imageHandler->smartCrop(['faceIndex' => 1, 'padding' => 10]);

        $this->assertEquals([
            'smartCrop' => [
                'faceIndex' => 1,
                'padding'   => 10
            ]
        ], $imageHandler->getOptions());
    }

    public function testQualityWithValidInput()
    {
        $imageHandler = new ImageHandler();
        $imageHandler->quality(75);

        $this->assertEquals([
            'quality' => 75
        ], $imageHandler->getOptions());
    }

    public function testSetRgbaWithValidInput()
    {
        $imageHandler = new ImageHandler();
        $imageHandler->setRgba(255, 255, 255, 0.5);

        $this->assertEquals([
            'rgba' => [
                'r'     => 255,
                'g'     => 255,
                'b'     => 255,
                'alpha' => 0.5
            ]
        ], $imageHandler->getOptions());
    }

    public function testContentModerationWithArray()
    {
        $imageHandler = new ImageHandler();
        $imageHandler->contentModeration([
            'minConfidence'    => 0.5,
            'blur'             => true,
            'moderationLabels' => ['explicit_nudity', 'suggestive']
        ]);

        $this->assertEquals([
            'contentModeration' => [
                'minConfidence'    => 0.5,
                'blur'             => true,
                'moderationLabels' => ['explicit_nudity', 'suggestive']
            ]
        ], $imageHandler->getOptions());
    }

    public function testCropWithValidInput()
    {
        $imageHandler = new ImageHandler();
        $imageHandler->crop(200, 200);

        $this->assertEquals([
            'crop' => [
                'width'  => 200,
                'height' => 200
            ]
        ], $imageHandler->getOptions());
    }

    public function testRotateWithValidInput()
    {
        $imageHandler = new ImageHandler();
        $imageHandler->rotate(180);

        $this->assertEquals([
            'rotate' => 180
        ], $imageHandler->getOptions());
    }

    public function testRoundCropWithArray()
    {
        $imageHandler = new ImageHandler();
        $imageHandler->roundCrop([
            'rx'   => 50,
            'ry'   => 50,
            'top'  => 10,
            'left' => 10
        ]);

        $this->assertEquals([
            'roundCrop' => [
                'rx'   => 50,
                'ry'   => 50,
                'top'  => 10,
                'left' => 10
            ]
        ], $imageHandler->getOptions());
    }

    public function testResizeWithInvalidInput()
    {
        $imageHandler = new ImageHandler();

        // Test with non-numeric width
        $this->expectException(TypeError::class);
        $imageHandler->resize(200, 'invalid', 'cover', 'black');

        // Test with non-numeric height
        $this->expectException(TypeError::class);
        $imageHandler->resize('invalid', 200, 'cover', 'black');

        // Test with invalid fit value
        $this->expectException(TypeError::class);
        $imageHandler->resize(200, 200, 'invalid', 'black');

        // Test with invalid color value
        $this->expectException(TypeError::class);
        $imageHandler->resize(200, 200, 'cover', 'invalid');
    }

    public function testSmartCropWithInvalidInput()
    {
        $imageHandler = new ImageHandler();

        // Test with non-array input
        $this->expectException(\InvalidArgumentException::class);
        $imageHandler->smartCrop('invalid');
    }

    public function testQualityWithInvalidInput()
    {
        $imageHandler = new ImageHandler();

        // Test with non-numeric quality
        $this->expectException(TypeError::class);
        $imageHandler->quality('invalid');
    }

    public function testSetRgbaWithInvalidInput()
    {
        $imageHandler = new ImageHandler();

        // Test with out of range red value
        $this->expectException(\InvalidArgumentException::class);
        $imageHandler->setRgba(256, 255, 255, 0.5);

        // Test with out of range green value
        $this->expectException(\InvalidArgumentException::class);
        $imageHandler->setRgba(255, 256, 255, 0.5);

        // Test with out of range blue value
        $this->expectException(\InvalidArgumentException::class);
        $imageHandler->setRgba(255, 255, 256, 0.5);

        // Test with out of range alpha value
        $this->expectException(\InvalidArgumentException::class);
        $imageHandler->setRgba(255, 255, 255, 1.5);
    }

    public function testContentModerationWithInvalidInput()
    {
        $imageHandler = new ImageHandler();

        // Test with non-array input
        $this->expectException(\InvalidArgumentException::class);
        $imageHandler->contentModeration('invalid');
    }

    public function testCropWithInvalidInput()
    {
        $imageHandler = new ImageHandler();

        // Test with non-numeric width and height
        $this->expectException(TypeError::class);
        $imageHandler->crop('invalid', 'invalid');
    }

    public function testRotateWithInvalidInput()
    {
        $imageHandler = new ImageHandler();

        // Test with non-numeric degrees
        $this->expectException(TypeError::class);
        $imageHandler->rotate('invalid');
    }

    public function testRoundCropWithInvalidInput()
    {
        $imageHandler = new ImageHandler();

        // Test with non-array input
        $this->expectException(\InvalidArgumentException::class);
        $imageHandler->roundCrop('invalid');
    }

    public function testMultipleEdits()
    {
        $imageHandler = new ImageHandler();

        // Apply multiple edits
        $imageHandler->resize(200, 200, 'cover', 'black');
        $imageHandler->quality(75);
        $imageHandler->rotate(180);

        // Check that the options property contains all edits
        $this->assertEquals([
            'resize' => [
                'width'      => 200,
                'height'     => 200,
                'fit'        => 'cover',
                'background' => 'black'
            ],
            'quality' => 75,
            'rotate'  => 180
        ], $imageHandler->getOptions());
    }

    /**
     * Test the url method.
     *
     * @return void
     */
    public function testUrl()
    {
        $imageHandler = new ImageHandler();

        $url = $imageHandler->createUrl('path/to/image.jpg');

        $this->assertStringContainsString('your-test-distribution-url/', $url);

        $urlParts = explode('/', $url);
        $edits    = json_decode(base64_decode($urlParts[1]), true);

        $this->assertEquals('path/to/image.jpg', $edits['key']);
    }

    public function testUrlIsCorrectFormatAndBase64Encoded()
    {
        $imageHandler = new ImageHandler();
        $imageHandler->setOptions(['width' => 200, 'height' => 200]);

        // Get the distribution URL from the configuration
        $distributionUrl = config('aws-image-handler.distributionUrl');

        // Set a mock distribution URL for testing
        $reflection = new \ReflectionClass($imageHandler);
        $property   = $reflection->getProperty('distributionUrl');
        $property->setAccessible(true);
        $property->setValue($imageHandler, $distributionUrl);

        $url = $imageHandler->createUrl('test.jpg');

        $this->assertMatchesRegularExpression('/^' . preg_quote($distributionUrl, '/') . '\/[a-zA-Z0-9+\/=]+$/', $url);

        // Check that the base64-encoded part decodes to the correct data
        $base64 = str_replace($distributionUrl . '/', '', $url);
        $data   = json_decode(base64_decode($base64), true);

        $this->assertEquals([
            'bucket' => config('aws-image-handler.defaultBucket'), // Get the default bucket from the configuration
            'key'    => 'test.jpg',
            'edits'  => ['width' => 200, 'height' => 200],
        ], $data);
    }
}
