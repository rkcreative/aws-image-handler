<?php

return function ($options = true) {
    if (is_bool($options)) {
        $this->options['smartCrop'] = $options;
        return $this;
    } elseif (is_array($options)) {
        $this->options['smartCrop'] = [
            'faceIndex' => $options['faceIndex'] ?? 0,
            'padding'   => $options['padding'] ?? 0
        ];
        return $this;
    } else {
        throw new \InvalidArgumentException('Invalid smartCrop value. Must be a boolean or an array.');
    }
};
