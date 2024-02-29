<?php

return [
    'distributionUrl' => env('AWS_IMAGE_HANDLER_URL', 'default-url'),
    'defaultBucket'   => env('AWS_IMAGE_HANDLER_S3_BUCKET', 'default-bucket'),
];
