<?php

namespace App\Services\External\PostHog\ErrorTracking\Request;

use App\Services\External\PostHog\BaseRequest;
use App\Services\External\PostHog\ErrorTracking\Entity\Property;

class ErrorTrackingRequest extends BaseRequest
{
    protected string $event = '$exception';

    protected Property $property;

    public function __construct(
        Property $property,
    ) {
        $this->property = $property;
    }

    public function toArray(): array
    {
        return array_filter([
            'event' => $this->event,
            'distinct_id' => $this->property->getDistinctId(),
            'properties' => $this->property->toArray(),
        ], fn($value) => $value !== null);
    }
}
