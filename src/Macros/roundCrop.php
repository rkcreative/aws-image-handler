<?php

return function ($imageHandler, $options = true) {
    if (is_bool($options)) {
        $imageHandler->options['roundCrop'] = $options;
    } elseif (is_array($options)) {
        $imageHandler->options['roundCrop'] = [
            'rx'   => $options['rx'] ?? null,
            'ry'   => $options['ry'] ?? null,
            'top'  => $options['top'] ?? null,
            'left' => $options['left'] ?? null
        ];
    } else {
        throw new \InvalidArgumentException('Invalid roundCrop value. Must be a boolean or an array.');
    }
};
