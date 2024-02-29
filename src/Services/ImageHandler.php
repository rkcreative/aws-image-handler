<?php
namespace Rkcreative\AwsImageHandler\Services;

use Illuminate\Support\Traits\Macroable;

class ImageHandler
{
    use Macroable;

    protected $distributionUrl;
    protected $defaultBucket;
    protected $options = [];

    public function __construct()
    {
        $this->distributionUrl = config('aws-image-handler.distributionUrl');
        $this->defaultBucket   = config('aws-image-handler.defaultBucket');

        if (!$this->distributionUrl) {
            throw new \Exception('Distribution URL is not configured.');
        }

        if (!$this->defaultBucket) {
            throw new \Exception('Default bucket is not configured.');
        }
    }

    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }
    }

    public function getOptions()
    {
        return $this->options;
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

    public function url(string $imageKey, string $bucket = null): string
    {
        $bucket = $bucket ?? $this->defaultBucket;

        if (!$imageKey) {
            throw new \Exception('Image key is not set.');
        }

        $edits  = base64_encode(json_encode(['bucket' => $bucket, 'key' => $imageKey, 'edits' => $this->options]));

        return "{$this->distributionUrl}/{$edits}";
    }
}
