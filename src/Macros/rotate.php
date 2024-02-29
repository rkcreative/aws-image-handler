<?php

return function ($imageHandler, int $degrees = 0) {
    if ($degrees < 0 || $degrees > 360) {
        throw new \InvalidArgumentException('Invalid rotation value. Must be between 0 and 360.');
    }

    $imageHandler->options['rotate'] = $degrees;
};
