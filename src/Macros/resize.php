<?php

return function ($imageHandler, int $width = 100, int $height = 100, string $fit = 'cover', $color = null) {
    $validFits = ['cover', 'contain', 'fill', 'inside', 'outside'];

    if (!in_array($fit, $validFits)) {
        throw new \InvalidArgumentException('Invalid fit value. Allowed values are ' . implode(', ', $validFits) . '.');
    }

    if (!is_string($color) && !is_array($color)) {
        throw new \InvalidArgumentException('Invalid color value. Must be a string or an array.');
    }

    $color = $color ?? $this->options['rgba'] ?? 'black';

    $imageHandler->options['resize'] = [
        'width'      => $width,
        'height'     => $height,
        'fit'        => $fit,
        'background' => $color
    ];
};
