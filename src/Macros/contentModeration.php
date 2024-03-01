<?php

return function ($options = true) {
    if (is_bool($options)) {
        $this->options['contentModeration'] = $options;
        return $this;
    } elseif (is_array($options)) {
        if (isset($options['moderationLabels']) && !is_array($options['moderationLabels'])) {
            throw new \InvalidArgumentException('Invalid moderationLabels value. Must be an array.');
        }
        $this->options['contentModeration'] = [
            'minConfidence'    => $options['minConfidence'] ?? null,
            'blur'             => $options['blur'] ?? null,
            'moderationLabels' => $options['moderationLabels'] ?? null
        ];
        return $this;
    } else {
        throw new \InvalidArgumentException('Invalid contentModeration value. Must be a boolean or an array.');
    }
};
