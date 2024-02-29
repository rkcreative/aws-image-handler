M<?php

return function ($imageHandler, int $r = 0, int $g = 0, int $b = 0, float $a = 1.0) {
    if ($r < 0 || $r > 255) {
        throw new \InvalidArgumentException('Invalid red value. Must be between 0 and 255.');
    }

    if ($g < 0 || $g > 255) {
        throw new \InvalidArgumentException('Invalid green value. Must be between 0 and 255.');
    }

    if ($b < 0 || $b > 255) {
        throw new \InvalidArgumentException('Invalid blue value. Must be between 0 and 255.');
    }

    if ($a < 0.0 || $a > 1.0) {
        throw new \InvalidArgumentException('Invalid alpha value. Must be between 0.0 and 1.0.');
    }

    $imageHandler->options['rgba'] = [
        'r'     => $r,
        'g'     => $g,
        'b'     => $b,
        'alpha' => $a
    ];
};
