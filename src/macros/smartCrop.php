<?php

return function ($imageHandler, $options = true) {
    if (is_bool($options)) {
        $imageHandler->options['smartCrop'] = $options;
    } elseif (is_array($options)) {
        $imageHandler->options['smartCrop'] = [
            'faceIndex' => $options['faceIndex'] ?? 0,
            'padding'   => $options['padding'] ?? 0
        ];
    } else {
        throw new \InvalidArgumentException('Invalid smartCrop value. Must be a boolean or an array.');
    }
};
