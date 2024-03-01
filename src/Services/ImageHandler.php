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
        // Log the options property before returning it
        error_log('From getOptions: ' . print_r($this->options, true), 3, __DIR__ . '/../../logfile.log');

        return $this->options;
    }

    public function createUrl(string $imageKey, string $bucket = null): string
    {
        $bucket = $bucket ?? $this->defaultBucket;

        if (!$imageKey) {
            throw new \Exception('Image key is not set.');
        }

        if (!$bucket) {
            throw new \Exception('S3 bucket is not set.');
        }

        $edits  = base64_encode(json_encode(['bucket' => $bucket, 'key' => $imageKey, 'edits' => $this->options]));

        return "{$this->distributionUrl}/{$edits}";
    }
}
