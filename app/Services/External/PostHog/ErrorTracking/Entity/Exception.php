<?php

namespace App\Services\External\PostHog\ErrorTracking\Entity;

class Exception
{
    protected string $type;

    protected string $value;

    protected Mechanism|null $mechanism = null;

    protected Stacktrace|null $stacktrace = null;

    public function __construct(
        string $type,
        string $value,
        Mechanism|null $mechanism = null,
        Stacktrace|null $stacktrace = null,
    ) {
        $this->type = $type;
        $this->value = $value;
        $this->mechanism = $mechanism;
        $this->stacktrace = $stacktrace;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'value' => $this->value,
            'mechanism' => $this->mechanism?->toArray(),
            'stacktrace' => $this->stacktrace?->toArray(),
        ], fn($value) => $value !== null);
    }
}
