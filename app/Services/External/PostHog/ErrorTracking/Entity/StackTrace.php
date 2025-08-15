<?php

namespace App\Services\External\PostHog\ErrorTracking\Entity;

class StackTrace
{
    protected string $type;

    /**
     * @var StackTraceFrame[]
     */
    protected array $stacktraceFrames = [];

    public function __construct(
        string $type,
        array $stacktraceFrames = [],
    ) {
        $this->type = $type;
        $this->stacktraceFrames = $stacktraceFrames;
    }

    public function toArray(): array
    {
        $frames = array_map(function ($frame) {
            return $frame->toArray();
        }, $this->stacktraceFrames);

        return array_filter([
            'type' => $this->type,
            'frames' => $frames,
        ], fn($value) => $value !== null);
    }
}
