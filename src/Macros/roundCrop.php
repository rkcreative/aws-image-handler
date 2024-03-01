<?php

return function ($options = true) {
    if (is_bool($options)) {
        $this->options['roundCrop'] = $options;
        return $this;
    } elseif (is_array($options)) {
        $this->options['roundCrop'] = [
            'rx'   => $options['rx'] ?? null,
            'ry'   => $options['ry'] ?? null,
            'top'  => $options['top'] ?? null,
            'left' => $options['left'] ?? null
        ];
        return $this;
    } else {
        throw new \InvalidArgumentException('Invalid roundCrop value. Must be a boolean or an array.');
    }
};
