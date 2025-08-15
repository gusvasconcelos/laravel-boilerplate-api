<?php

namespace App\Services\External\PostHog\ErrorTracking\Entity;

class Mechanism
{
    protected string $handled;

    protected string $synthetic;

    public function __construct(
        string $handled,
        string $synthetic,
    ) {
        $this->handled = $handled;
        $this->synthetic = $synthetic;
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($value) => $value !== null);
    }
}
