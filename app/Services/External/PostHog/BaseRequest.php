<?php

namespace App\Services\External\PostHog;

use Illuminate\Contracts\Support\Arrayable;

abstract class BaseRequest implements Arrayable
{
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toJsonPretty(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($value) => $value !== null);
    }
}
