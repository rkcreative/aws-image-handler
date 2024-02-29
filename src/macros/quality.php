<?php

return function ($imageHandler, int $value = 75) {
    if ($value < 0 || $value > 100) {
        throw new \InvalidArgumentException('Invalid quality value. Must be between 0 and 100.');
    }

    $imageHandler->options['quality'] = $value;
};
