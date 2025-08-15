<?php

namespace App\Services\External\PostHog\ErrorTracking\Entity;

class Property
{
    protected string|null $distinctId = null;

    /**
     * @var Exception[]
     */
    protected array $exceptions = [];

    protected string|null $exceptionFingerprint = null;

    public function __construct(
        string|null $distinctId = null,
        array $exceptions = [],
        string|null $exceptionFingerprint = null,
    ) {
        $this->distinctId = $distinctId;
        $this->exceptions = $exceptions;
        $this->exceptionFingerprint = $exceptionFingerprint;
    }

    public function toArray(): array
    {
        return array_filter([
            'distinct_id' => $this->distinctId,
            '$exception_list' => array_map(fn(Exception $exception) => $exception->toArray(), $this->exceptions),
            '$exception_fingerprint' => $this->exceptionFingerprint,
        ], fn($value) => $value !== null);
    }

    public function getDistinctId(): ?string
    {
        return $this->distinctId;
    }
}
