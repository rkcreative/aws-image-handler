<?php

return function ($imageHandler, $options = true) {
    if (is_bool($options)) {
        $imageHandler->options['contentModeration'] = $options;
    } elseif (is_array($options)) {
        if (isset($options['moderationLabels']) && !is_array($options['moderationLabels'])) {
            throw new \InvalidArgumentException('Invalid moderationLabels value. Must be an array.');
        }
        $imageHandler->options['contentModeration'] = [
            'minConfidence'    => $options['minConfidence'] ?? null,
            'blur'             => $options['blur'] ?? null,
            'moderationLabels' => $options['moderationLabels'] ?? null
        ];
    } else {
        throw new \InvalidArgumentException('Invalid contentModeration value. Must be a boolean or an array.');
    }
};
