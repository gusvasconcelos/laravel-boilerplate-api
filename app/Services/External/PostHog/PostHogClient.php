<?php

namespace App\Services\External\PostHog;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

abstract class PostHogClient
{
    protected string $baseUrl;

    protected string $apiKey;

    protected PendingRequest $client;

    public function __construct()
    {
        $this->baseUrl = config('external.posthog.host');

        $this->apiKey = config('external.posthog.api_key');

        $this->client = Http::asJson()->baseUrl($this->baseUrl);
    }
}
