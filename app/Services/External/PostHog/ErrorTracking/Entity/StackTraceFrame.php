<?php

namespace App\Services\External\PostHog\ErrorTracking\Entity;

class StackTraceFrame
{
    protected string $filename;

    protected int $line;

    protected string $message;

    protected int $statusCode;

    protected string $errorCode;

    protected array|null $details = null;

    protected string|null $sql = null;

    protected string|null $stack = null;

    public function __construct(
        string $filename,
        int $line,
        string $message,
        int $statusCode,
        string $errorCode,
        array|null $details = null,
        string|null $sql = null,
        string|null $stack = null,
    ) {
        $this->filename = $filename;
        $this->line = $line;
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
        $this->details = $details;
        $this->sql = $sql;
        $this->stack = $stack;
    }

    public function toArray(): array
    {
        return array_filter([
            'platform' => 'custom',
            'lang' => 'php',
            'filename' => $this->filename,
            'function' => 'notFound',
            'lineno' => $this->line,
            'message' => $this->message,
            'status' => $this->statusCode,
            'error_code' => $this->errorCode,
            'details' => $this->details,
            'sql' => $this->sql,
            'stack' => $this->stack,
        ], fn($value) => $value !== null);
    }
}
