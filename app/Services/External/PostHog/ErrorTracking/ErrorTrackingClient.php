<?php

namespace App\Services\External\PostHog\ErrorTracking;

use App\Services\External\PostHog\ErrorTracking\Request\ErrorTrackingRequest;
use App\Services\External\PostHog\PostHogClient;

class ErrorTrackingClient extends PostHogClient
{
    public function capture(ErrorTrackingRequest $request): void
    {
        $payload = array_merge(
            ['api_key' => $this->apiKey],
            $request->toArray(),
        );

        $this->client->post('/i/v0/e/', $payload);
    }
}
