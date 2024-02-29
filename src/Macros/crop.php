<?php

return function ($imageHandler, int $width = 100, int $height = 100) {
    if ($width <= 0 || $height <= 0) {
        throw new \InvalidArgumentException('Invalid crop dimensions. Width and height must be greater than 0.');
    }

    $imageHandler->options['crop'] = [
        'width'  => $width,
        'height' => $height
    ];
};
