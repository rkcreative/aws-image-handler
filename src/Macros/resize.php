<?php

return function (?int $width = 100, ?int $height = 100, string $fit = 'cover', $color = null) {
    $validFits = ['cover', 'contain', 'fill', 'inside', 'outside'];

    if (!in_array($fit, $validFits)) {
        throw new \InvalidArgumentException('Invalid fit value. Allowed values are ' . implode(', ', $validFits) . '.');
    }

    if ($color !== null && !is_string($color) && !is_array($color)) {
        throw new \InvalidArgumentException('Invalid color value. Must be a string or an array.');
    }

    $color = $color ?? $this->options['rgba'] ?? 'black';

    $this->options['resize'] = [
        'width'      => $width,
        'height'     => $height,
        'fit'        => $fit,
        'background' => $color
    ];

    // Log the options property after setting it
    error_log('From resize: ' . print_r($this->options, true), 3, __DIR__ . '/../../logfile.log');

    return $this;
};
